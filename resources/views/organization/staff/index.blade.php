@extends('layouts.admin')

@section('title', 'Manage Staff | ResQNet')
@section('header', 'Manage Organization Staff')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Staff List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Current Staff Members</h3>
                <p class="text-sm text-gray-500">Everyone who has access to your organization dashboard.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-900 font-medium">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($staffMembers as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $member->name }}</td>
                            <td class="px-6 py-4">{{ $member->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    {{ $member->roles->first()?->name ?? 'Staff' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($member->id !== auth()->id())
                                <form method="POST" action="{{ route('organization.staff.remove', $member->id) }}" onsubmit="return confirm('Are you sure you want to remove this staff member?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Remove</button>
                                </form>
                                @else
                                <span class="text-gray-400 text-xs italic">You</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Invite Staff Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Invite New Staff</h3>
        <p class="text-sm text-gray-500 mb-6">Send an invitation to a volunteer or staff member to join your dashboard.</p>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 p-3 rounded text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organization.staff.invite') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Role</label>
                <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                    <option value="staff">Volunteer / Staff</option>
                    <option value="vet">Veterinarian</option>
                    <option value="organization">Organization Admin</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-4 rounded-md transition">
                Send Invitation
            </button>
        </form>
    </div>

</div>
@endsection
