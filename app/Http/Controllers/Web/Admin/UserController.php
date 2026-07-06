<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\UserSuspendedNotification;
use App\Notifications\UserDeletedNotification;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->where('id', '!=', auth()->id())->get();
        $usersJson = $users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'is_active' => $u->is_active,
            'role' => $u->roles->first()->name ?? 'User',
        ]);
        return view('admin.users.index', compact('users', 'usersJson'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,organization,public_user',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'role' => $user->getRoleNames()->first() ?? 'User',
            ],
        ]);
    }

    public function deactivate(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $user->is_active = false;
        $user->suspension_reason = $request->reason;
        $user->save();

        $user->notify(new UserSuspendedNotification($request->reason));

        return back()->with('success', 'User has been suspended successfully.');
    }

    public function reactivate(User $user)
    {
        $user->is_active = true;
        $user->suspension_reason = null;
        $user->save();

        return back()->with('success', 'User has been reactivated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $user->deletion_reason = $request->reason;
        $user->save();

        $user->notify(new UserDeletedNotification($request->reason));
        
        $user->delete();

        return back()->with('success', 'User has been permanently removed.');
    }
}
