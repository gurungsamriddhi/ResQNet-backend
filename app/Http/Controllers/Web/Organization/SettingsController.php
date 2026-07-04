<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $organization = auth()->user()?->organization;
        return view('organization.settings.index', compact('organization'));
    }

    public function update(Request $request)
    {
        $organization = auth()->user()?->organization;

        if (!$organization) {
            return back()->withErrors(['general' => 'No organization found.']);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $organization->logo = $path;
        }

        $organization->name = $request->name;
        $organization->description = $request->description;
        $organization->email = $request->email;
        $organization->phone = $request->phone;
        $organization->address = $request->address;
        $organization->save();

        return redirect()->route('organization.settings.index')
            ->with('success', 'Organization settings updated successfully.');
    }
}
