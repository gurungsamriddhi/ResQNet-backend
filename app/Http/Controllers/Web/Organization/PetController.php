<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function changeStatus(Request $request)
    {
        // TODO: Implement changeStatus
        return response()->json(['message' => 'Not implemented yet - changeStatus']);
    }

    public function generateQr(Request $request)
    {
        // TODO: Implement generateQr
        return response()->json(['message' => 'Not implemented yet - generateQr']);
    }
}
