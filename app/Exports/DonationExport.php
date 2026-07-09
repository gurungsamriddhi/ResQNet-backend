<?php

namespace App\Exports;

use App\Models\Donation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DonationExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Donation::query()->orderBy('created_at', 'desc');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Purchase Order ID',
            'PIDX',
            'User ID',
            'Organization ID',
            'Donor Name',
            'Donor Email',
            'Donor Phone',
            'Is Anonymous',
            'Frequency',
            'Amount',
            'Payment Method',
            'Transaction ID',
            'Status',
            'Payment Payload',
            'Remarks',
            'Created At',
        ];
    }

    public function map($donation): array
    {
        return [
            $donation->id,
            $donation->purchase_order_id,
            $donation->pidx,
            $donation->user_id,
            $donation->organization_id,
            $donation->donor_name,
            $donation->donor_email,
            $donation->donor_phone,
            $donation->is_anonymous ? 'Yes' : 'No',
            $donation->frequency,
            $donation->amount,
            $donation->payment_method,
            $donation->transaction_id,
            $donation->status,
            $donation->payment_payload ? json_encode($donation->payment_payload) : '',
            $donation->remarks,
            $donation->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
