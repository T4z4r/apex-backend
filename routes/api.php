<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PropertyController;
use App\Http\Controllers\API\UnitController;
use App\Http\Controllers\API\LeaseController;
use App\Http\Controllers\API\MaintenanceController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\DisputeController;
use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('properties', [PropertyController::class, 'index']);
    Route::get('properties/{id}', [PropertyController::class, 'show']);
    Route::post('properties', [PropertyController::class, 'store']);

    Route::post('properties/{id}/units', [UnitController::class, 'store']);

    Route::post('leases/{unit_id}/request', [LeaseController::class, 'requestLease']);
    Route::get('leases/{id}', [LeaseController::class, 'show']);
    Route::post('leases/{id}/sign', [LeaseController::class, 'sign']);


    Route::get('maintenance', [MaintenanceController::class, 'index']);
    Route::post('maintenance', [MaintenanceController::class, 'store']);
    Route::patch('maintenance/{id}', [MaintenanceController::class, 'update']);


    // Agents
    Route::post('agents', [AgentController::class, 'store']);
    Route::get('agents', [AgentController::class, 'index']);
    Route::post('agents/{id}/verify', [AgentController::class, 'verify']); // Admin only

    // Disputes
    Route::post('disputes', [DisputeController::class, 'store']); // Tenant/Landlord
    Route::get('disputes', [DisputeController::class, 'index']); // Admin only
    Route::patch('disputes/{id}', [DisputeController::class, 'update']); // Admin only



    // Conversations
    Route::get('conversations', [ConversationController::class, 'index']);
    Route::post('conversations', [ConversationController::class, 'store']);

    // Messages
    Route::get('conversations/{id}/messages', [MessageController::class, 'index']);
    Route::post('conversations/{id}/messages', [MessageController::class, 'store']);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
