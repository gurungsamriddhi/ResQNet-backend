<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        // TODO: Implement index
        return response()->json(['message' => 'Not implemented yet - index']);
    }

    public function deactivate(Request $request)
    {
        // TODO: Implement deactivate
        return response()->json(['message' => 'Not implemented yet - deactivate']);
    }
}
