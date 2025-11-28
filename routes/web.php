<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;


// Public routes - accessible to everyone
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/match-job', function () {
    return view('match-job');
})->name('match-job');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}/details', [JobController::class, 'getJobDetails'])->name('jobs.details');
Route::post('/jobs/{id}/apply', [JobController::class, 'applyForJob'])->name('jobs.apply');
Route::post('/jobs/{id}/save', [JobController::class, 'saveJob'])->name('jobs.save');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

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
    Route::get('/my-applications', [JobController::class, 'myApplications'])->name('my-applications');
    Route::post('/applications/{id}/withdraw', [JobController::class, 'withdrawApplication'])->name('applications.withdraw');

    Route::get('/saved-jobs', [JobController::class, 'savedJobs'])->name('saved-jobs');
});

// More section routes (public)
Route::get('/skill-development', function () {
    return view('skill-development');
})->name('skill-development');

Route::get('/career-guidance', function () {
    return view('career-guidance');
})->name('career-guidance');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.edit');
    
    // Profile picture update route
    Route::patch('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    
    // Keep only essential profile routes
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Skill Development Route
Route::get('/skill-development', [CourseController::class, 'index'])->name('skill-development');
Route::get('/skill-development/search', [CourseController::class, 'search'])->name('skill-development.search');
Route::get('/learning-activity', [CourseController::class, 'learningActivity'])->name('learning-activity');

Route::get('/skill-development/{slug}', [CourseController::class, 'show'])->name('skill-development.show');

Route::post('/checkout/{id}', [CheckoutController::class, 'checkout'])->name('stripe.checkout');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('stripe.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('stripe.cancel');

Route::get('/learning-activities', [CourseController::class, 'learningActivities'])
    ->middleware('auth')
    ->name('learning.activities');




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
            Route::get('/', [App\Http\Controllers\Employer\JobController::class, 'manage'])->name('manage');
            Route::get('/debug-search', [App\Http\Controllers\Employer\JobController::class, 'debugSearch'])->name('debug');
            Route::get('/create', [App\Http\Controllers\Employer\JobController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Employer\JobController::class, 'store'])->name('store');
            Route::get('/{job}', [App\Http\Controllers\Employer\JobController::class, 'show'])->name('show');
            Route::get('/{job}/edit', [App\Http\Controllers\Employer\JobController::class, 'edit'])->name('edit');
            Route::put('/{job}', [App\Http\Controllers\Employer\JobController::class, 'update'])->name('update');
            Route::delete('/{job}', [App\Http\Controllers\Employer\JobController::class, 'destroy'])->name('destroy');
        });

        // Company profile routes
        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/profile', function () {
                $employer = Auth::guard('employer')->user();
                return view('employer.company.profile', compact('employer'));
            })->name('profile');
        });

        // User profile routes
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/profile', function () {
                $employer = Auth::guard('employer')->user();
                return view('employer.user.profile', compact('employer'));
            })->name('profile');
        });
    });
});

require __DIR__ . '/auth.php';
