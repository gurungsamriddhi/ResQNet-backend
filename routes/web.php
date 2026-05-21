<?php

use Illuminate\Support\Facades\Route;

// This controls http://127.0.0.1:8000/
Route::get('/', function () {
    return view('welcome'); 
});

// This controls http://127.0.0.1:8000/admin/dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});