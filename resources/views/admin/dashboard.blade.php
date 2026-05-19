@extends('layouts.app')

@section('content')
    <h2>Super Admin Dashboard</h2>
    
    <h3>Pending Organizations</h3>

    @if($pendingOrganizations->isEmpty())
        <p>No organizations are pending approval at this time.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>Organization Name</th>
                    <th>User / Manager</th>
                    <th>Email</th>
                    <th>Registration Number</th>
                    <th>Document</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingOrganizations as $org)
                    <tr>
                        <td>{{ $org->name }}</td>
                        <td>{{ $org->user->name ?? 'N/A' }}</td>
                        <td>{{ $org->email }}</td>
                        <td>{{ $org->registration_number }}</td>
                        <td>
                            @if($org->hasMedia('registration_documents'))
                                <a href="{{ $org->getFirstMediaUrl('registration_documents') }}" target="_blank">View Document</a>
                            @else
                                No Document
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.organizations.approve', $org->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="color: green; cursor: pointer;">Approve</button>
                            </form>
                            
                            <form action="{{ route('admin.organizations.reject', $org->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="color: red; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this organization?')">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
