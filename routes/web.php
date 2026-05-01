<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
    return view('home');
});

// Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Superadmin creates admins
Route::middleware(['role:superadmin'])->group(function () {
    Route::get('/superadmin/create-admin', [AdminController::class, 'showCreateForm'])
        ->name('superadmin.create-admin');

    Route::post('/superadmin/create-admin', [AdminController::class, 'store'])
        ->name('superadmin.store-admin');  // <-- this is the one your Blade form needs
});

// Admin dashboard & user management
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
         ->name('admin.dashboard');   // ✅ add name here

    Route::get('/admin/create-user', [AdminController::class, 'showCreateUserForm'])
         ->name('admin.create-user');

    Route::post('/admin/create-user', [AdminController::class, 'storeUser'])
         ->name('admin.store-user');
});


Route::middleware('auth', 'role:admin,member')->group(function () {
    Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
    Route::get('/urls/create', function () {
        return view('urls.create');   // shows the form
    })->name('urls.create');
    Route::post('/urls', [UrlController::class, 'store'])->name('urls.store');
});

// Public redirect route
Route::get('/s/{shortCode}', [UrlController::class, 'redirect'])->name('urls.redirect');

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])
         ->name('member.dashboard');
});
