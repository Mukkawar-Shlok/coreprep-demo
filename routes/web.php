<?php

use App\Livewire\Counter;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\Dashboard;
use App\Livewire\TakeTest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('home');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/test/{testId}', TakeTest::class)->name('take-test');
});

// Logout route (can be accessed via POST for security)
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login')->with('success', 'You have been logged out successfully.');
})->name('logout')->middleware('auth');
