<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PropertyController;
use App\Http\Controllers\Web\UnitController;
use App\Http\Controllers\Web\LeaseController;
use App\Http\Controllers\Web\MaintenanceController;
use App\Http\Controllers\Web\DisputeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\AgentController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\PermissionController;
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
    return view('welcome');
});

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('leases/{lease}/sign', [App\Http\Controllers\Web\LeaseController::class, 'sign'])->name('leases.sign');
    Route::get('leases/{lease}/generate-pdf', [App\Http\Controllers\Web\LeaseController::class, 'generatePdf'])->name('leases.generate-pdf');
    Route::post('agents/{agent}/verify', [App\Http\Controllers\Web\AgentController::class, 'verify'])->name('agents.verify');


    Route::middleware(['role:admin|landlord|agent'])->group(function(){
Route::resource('properties', PropertyController::class);
Route::resource('units', UnitController::class);
Route::resource('leases', LeaseController::class);
});


Route::middleware(['role:admin|landlord|tenant'])->group(function(){
Route::resource('maintenance', MaintenanceController::class);
});


Route::middleware(['role:admin'])->group(function(){
Route::resource('disputes', DisputeController::class);
Route::resource('users', UserController::class);
Route::resource('agents', AgentController::class);
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
});
});

require __DIR__ . '/auth.php';
