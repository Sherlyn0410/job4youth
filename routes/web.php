<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible to everyone
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}/details', [JobController::class, 'getJobDetails'])->name('jobs.details');
Route::post('/jobs/{id}/apply', [JobController::class, 'applyForJob'])->name('jobs.apply');
Route::post('/jobs/{id}/save', [JobController::class, 'saveJob'])->name('jobs.save');

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

// Update the employer routes section
Route::prefix('employer')->name('employer.')->group(function () {
    // These routes should NOT have any auth middleware
    Route::get('/login', [App\Http\Controllers\Employer\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Employer\AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [App\Http\Controllers\Employer\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\Employer\AuthController::class, 'register'])->name('register.submit');
    
    // Logout route (only for authenticated employers)
    Route::post('/logout', [App\Http\Controllers\Employer\AuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth:employer');
    
    // Protected employer routes
    Route::middleware('auth:employer')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Employer\DashboardController::class, 'index'])->name('dashboard');
        
        // Jobs routes
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [App\Http\Controllers\Employer\JobController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Employer\JobController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Employer\JobController::class, 'store'])->name('store');
            Route::get('/{job}', [App\Http\Controllers\Employer\JobController::class, 'show'])->name('show');
            Route::get('/{job}/edit', [App\Http\Controllers\Employer\JobController::class, 'edit'])->name('edit');
            Route::put('/{job}', [App\Http\Controllers\Employer\JobController::class, 'update'])->name('update');
            Route::delete('/{job}', [App\Http\Controllers\Employer\JobController::class, 'destroy'])->name('destroy');
        });
        
        // Company profile routes
        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/profile', function() { 
                $employer = Auth::guard('employer')->user();
                return view('employer.company.profile', compact('employer')); 
            })->name('profile');
        });
        
        // User profile routes
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/profile', function() { 
                $employer = Auth::guard('employer')->user();
                return view('employer.user.profile', compact('employer')); 
            })->name('profile');
        });
    });
});

require __DIR__.'/auth.php';
