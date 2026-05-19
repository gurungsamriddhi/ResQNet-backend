<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdoptionController extends Controller
{
    public function index()
    {
        // TODO: Implement index
        return response()->json(['message' => 'Not implemented yet - index']);
    }

    public function approve(Request $request)
    {
        // TODO: Implement approve
        return response()->json(['message' => 'Not implemented yet - approve']);
    }

    public function reject(Request $request)
    {
        // TODO: Implement reject
        return response()->json(['message' => 'Not implemented yet - reject']);
    }
}
