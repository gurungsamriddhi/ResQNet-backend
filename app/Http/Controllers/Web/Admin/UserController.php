<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function deactivate(Request $request)
    {
        // TODO: Implement deactivate
        return response()->json(['message' => 'Not implemented yet - deactivate']);
    }
}
