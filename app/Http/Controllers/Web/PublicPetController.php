<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pet;

class PublicPetController extends Controller
{
    public function showByQr(string $qrCodeId)
    {
        //with used for eager loading when accessing the related model data in the same query . Avoids N+1 query problem 
        //otherwise it will make multiple queries to fetch the related model data 
        //For example, if we want to fetch the pet and its organization and vet data, we can use with([]) to fetch all the data in the same query 
        $pet = Pet::with([
            'media',
            'organization.user',
            'medicalRecords.vet',
            'vaccinations.vet',
            'medications.vet',
        ])
            ->where('qr_code_id', $qrCodeId)
            ->firstOrFail();

        if ($pet->isSuspended()) {
            abort(403, 'This pet is suspended.');
        }

        return view('public.pets.qr-show', [
            'pet' => $pet,
            'apiUrl' => url('/api/pets/qr/' . $pet->qr_code_id),
            'appDeepLink' => 'resqnet://pets/qr/' . $pet->qr_code_id,
        ]);
    }
}
