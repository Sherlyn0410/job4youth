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
            Route::get('/', function() { 
                return view('employer.jobs.index', ['jobs' => collect()]); 
            })->name('index');
            Route::get('/create', function() { 
                return view('employer.jobs.create'); 
            })->name('create');
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
