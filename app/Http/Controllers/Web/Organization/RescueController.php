<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RescueController extends Controller
{
    public function index()
    {
        return view('admin.rescue.index');
    }

    public function updateStatus(Request $request)
    {
        // TODO: Implement updateStatus
        return response()->json(['message' => 'Not implemented yet - updateStatus']);
    }

    public function linkToPet(Request $request)
    {
        // TODO: Implement linkToPet
        return response()->json(['message' => 'Not implemented yet - linkToPet']);
    }
}
