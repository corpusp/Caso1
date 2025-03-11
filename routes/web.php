<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ConductorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MVPController;


Route::get('/', function () {
    return view('mvp'); // O cualquier otra vista que tengas
});

Route::get('/mvp', [MVPController::class, 'index'])->name('mvp');
Route::get('/users', [MvpController::class, 'getUsers']);
Route::post('/users', [MVPController::class, 'addUser']);
Route::delete('/users/{id}', [MVPController::class, 'deleteUser']);



Route::middleware(['auth'])->group(function () {
   
    Route::resource('usuarios', UsuarioController::class);
    
    // CRUD de tours
    Route::resource('tours', TourController::class);
    
    // CRUD de horarios
    Route::resource('horarios', HorarioController::class);
    
    // CRUD de reservas
    Route::resource('reservas', ReservaController::class);
    
    // CRUD de vehículos
    Route::resource('vehiculos', VehiculoController::class);
    
    // CRUD de conductores
    Route::resource('conductores', ConductorController::class);
});

Route::middleware(['auth'])->get('/menu', function () {
    $user = Auth::check() ? Auth::user() : null;
    return view('menu', compact('user'));
})->name('menu');
// CRUD de usuarios

//auth

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protegemos la ruta dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');



