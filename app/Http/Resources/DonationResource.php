<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'frequency' => $this->frequency,
            'status' => $this->status,
            'is_anonymous' => $this->is_anonymous,
            'donor_name' => $this->when(! $this->is_anonymous, $this->donor_name),
            'donor_email' => $this->when(! $this->is_anonymous, $this->donor_email),
            'donor_phone' => $this->when(! $this->is_anonymous, $this->donor_phone),
            'organization_id' => $this->organization_id,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
