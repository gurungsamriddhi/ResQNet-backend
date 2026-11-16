<?php

namespace App\Services;

use App\Events\DonationCompletedEvent;
use App\Models\Donation;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KhaltiService
{
    protected string $secretKey;
    protected string $baseUrl;
    protected string $websiteUrl;

    public function __construct()
    {
        $this->secretKey = config('khalti.secret_key');
        $this->baseUrl = config('khalti.base_url');
        $this->websiteUrl = config('khalti.website_url');
    }

    public function initiatePayment(array $params): array
    {
        $payload = [
            'return_url' => $params['return_url'],
            'website_url' => $this->websiteUrl,
            'amount' => (int) round($params['amount'] * 100),
            'purchase_order_id' => $params['purchase_order_id'],
            'purchase_order_name' => $params['purchase_order_name'] ?? 'Donation to ResQNet',
        ];

        if (! empty($params['customer_info'])) {
            $payload['customer_info'] = $params['customer_info'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'epayment/initiate/', $payload);

        if ($response->failed()) {
            throw new RuntimeException(
                'Khalti initiate failed: ' . ($response->json()['detail'] ?? $response->body())
            );
        }

        return $response->json();
    }

    public function lookupPayment(string $pidx): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Khalti lookup failed: ' . ($response->json()['detail'] ?? $response->body())
            );
        }

        return $response->json();
    }

    public function isCompleted(array $lookupResult): bool
    {
        return ($lookupResult['status'] ?? '') === 'Completed';
    }

    /**
     * Apply a Khalti lookup result to a donation. Safe to call repeatedly:
     * a donation is completed (and its event fired) at most once, and only
     * when Khalti reports the exact amount that was requested.
     *
     * @return string one of: completed, pending, amount_mismatch
     */
    public function settle(Donation $donation, array $lookup): string
    {
        $donation->payment_payload = $lookup;

        if ($donation->status === 'completed') {
            $donation->save();

            return 'completed';
        }

        if (! $this->isCompleted($lookup)) {
            $donation->save();

            return 'pending';
        }

        // Khalti reports amounts in paisa
        $expected = (int) round(((float) $donation->amount) * 100);
        if ((int) ($lookup['total_amount'] ?? -1) !== $expected) {
            $donation->status = 'failed';
            $donation->save();

            return 'amount_mismatch';
        }

        $donation->status = 'completed';
        $donation->transaction_id = $lookup['transaction_id'] ?? null;
        $donation->save();

        DonationCompletedEvent::dispatch($donation);

        return 'completed';
    }
}
