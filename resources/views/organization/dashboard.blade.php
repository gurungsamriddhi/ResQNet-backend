@extends('layouts.admin')

@section('title', 'Organization Dashboard | ResQNet')
@section('header', 'Shelter Overview')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 font-medium text-sm">Active Pets</h3>
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <span>🐾</span>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['active_pets'] ?? 0 }}</div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 font-medium text-sm">Pending Adoptions</h3>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['pending_adoptions'] ?? 0 }}</div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 font-medium text-sm">Active Rescues</h3>
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['active_rescues'] ?? 0 }}</div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 font-medium text-sm">Total Donations</h3>
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                <span class="font-bold">Rs</span>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">Rs. {{ number_format($stats['total_donations'] ?? 0) }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('organization.pets.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition">
                + Add New Pet
            </a>
            <a href="{{ route('organization.adoptions.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-4 py-2 rounded-lg font-medium transition">
                Review Adoptions
            </a>
        </div>
    </div>

    <!-- We can add recent activity here later -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Activity</h3>
        <p class="text-gray-500 text-sm">No recent activity.</p>
    </div>
</div>
@endsection
