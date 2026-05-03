<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

// Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Superadmin routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/create-admin', function () {
            return view('superadmin.create-admin');
        })->name('superadmin.create-admin');

    Route::post('/create-admin', [ClientController::class, 'store'])
        ->name('superadmin.store-admin');

    Route::get('/clients', [ClientController::class, 'clients'])
        ->name('superadmin.clients'); 
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/create-user', [UserController::class, 'storeUser'])
         ->name('admin.store-user');
    
    Route::get('/create-user', function () {
            return view('admin.create-user');
        })->name('admin.create-user');
         
    Route::get('/members', [UserController::class, 'index'])
         ->name('admin.members');
});

Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
});

// Admin & Member routes
Route::middleware(['auth', 'role:admin,member,superadmin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])
         ->name('user.dashboard');

    Route::prefix('urls')->group(function () {
        Route::get('/create', function () {
            return view('urls.create');
        })->name('urls.create');

        Route::post('/', [UrlController::class, 'store'])->name('urls.store');

        Route::get('/download', [UrlController::class, 'download'])->name('urls.download');
    });
});

// Public redirect route
Route::get('/s/{shortCode}', [UrlController::class, 'redirect'])->name('urls.redirect');

