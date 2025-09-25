<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible to everyone
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{id}/details', [JobController::class, 'getJobDetails'])->name('jobs.details');

// Keep welcome as fallback for now
Route::get('/welcome', function () {
    return view('welcome');
});

// Protected routes - require authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// My Jobs routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/my-applications', function () {
        return view('my-applications');
    })->name('my-applications');
    
    Route::get('/saved-jobs', function () {
        return view('saved-jobs');
    })->name('saved-jobs');
});

// More section routes (public)
Route::get('/skill-development', function () {
    return view('skill-development');
})->name('skill-development');

Route::get('/career-guidance', function () {
    return view('career-guidance');
})->name('career-guidance');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Add these routes
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}/details', [JobController::class, 'getJobDetails'])->name('jobs.details');

Route::middleware('auth')->group(function () {
    Route::post('/jobs/{id}/save', [JobController::class, 'saveJob'])->name('jobs.save');
    Route::post('/jobs/{id}/apply', [JobController::class, 'applyForJob'])->name('jobs.apply');
});

require __DIR__.'/auth.php';
