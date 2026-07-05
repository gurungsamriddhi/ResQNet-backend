<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pet;

class PublicPetController extends Controller
{
    public function showByQr(string $qrCodeId)
    {
        $pet = Pet::with(['media', 'organization'])
            ->where('qr_code_id', $qrCodeId)
            ->firstOrFail();

        if ($pet->isSuspended()) {
            abort(403, 'This pet is suspended.');
        }

        $baseUrl = rtrim(config('app.url'), '/');

        return view('public.pets.qr-show', [
            'pet' => $pet,
            'apiUrl' => $baseUrl . '/api/pets/qr/' . $pet->qr_code_id,
            'appDeepLink' => 'resqnet://pets/qr/' . $pet->qr_code_id,
        ]);
    }
}
