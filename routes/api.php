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
use App\Http\Controllers\API\PlanController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\LanguageController;

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
    Route::put('properties/{id}', [PropertyController::class, 'update']);
    Route::delete('properties/{id}', [PropertyController::class, 'destroy']);

    Route::get('units', [UnitController::class, 'index']);
    Route::get('units/{id}', [UnitController::class, 'show']);
    Route::post('properties/{id}/units', [UnitController::class, 'store']);
    Route::put('units/{id}', [UnitController::class, 'update']);
    Route::delete('units/{id}', [UnitController::class, 'destroy']);

    Route::get('leases', [LeaseController::class, 'index']);
    Route::post('leases/{unit_id}/request', [LeaseController::class, 'requestLease']);
    Route::get('leases/{id}', [LeaseController::class, 'show']);
    Route::put('leases/{id}', [LeaseController::class, 'update']);
    Route::post('leases/{id}/sign', [LeaseController::class, 'sign']);
    Route::post('leases/{id}/generate-pdf', [LeaseController::class,'generatePdf']);
    Route::delete('leases/{id}', [LeaseController::class, 'destroy']);

    Route::get('maintenance', [MaintenanceController::class, 'index']);
    Route::get('maintenance/{id}', [MaintenanceController::class, 'show']);
    Route::post('maintenance', [MaintenanceController::class, 'store']);
    Route::patch('maintenance/{id}', [MaintenanceController::class, 'update']);
    Route::delete('maintenance/{id}', [MaintenanceController::class, 'destroy']);

    // Agents
    Route::get('agents', [AgentController::class, 'index']);
    Route::get('agents/{id}', [AgentController::class, 'show']);
    Route::post('agents', [AgentController::class, 'store']);
    Route::put('agents/{id}', [AgentController::class, 'update']);
    Route::post('agents/{id}/verify', [AgentController::class, 'verify']); // Admin only
    Route::delete('agents/{id}', [AgentController::class, 'destroy']);

    // Disputes
    Route::get('disputes', [DisputeController::class, 'index']); // Admin only
    Route::get('disputes/{id}', [DisputeController::class, 'show']);
    Route::post('disputes', [DisputeController::class, 'store']); // Tenant/Landlord
    Route::patch('disputes/{id}', [DisputeController::class, 'update']); // Admin only
    Route::delete('disputes/{id}', [DisputeController::class, 'destroy']);

    // Conversations
    Route::get('conversations', [ConversationController::class, 'index']);
    Route::get('conversations/{id}', [ConversationController::class, 'show']);
    Route::post('conversations', [ConversationController::class, 'store']);
    Route::put('conversations/{id}', [ConversationController::class, 'update']);
    Route::delete('conversations/{id}', [ConversationController::class, 'destroy']);

    // Messages
    Route::get('conversations/{id}/messages', [MessageController::class, 'index']);
    Route::get('conversations/{id}/messages/{messageId}', [MessageController::class, 'show']);
    Route::post('conversations/{id}/messages', [MessageController::class, 'store']);
    Route::put('conversations/{id}/messages/{messageId}', [MessageController::class, 'update']);
    Route::delete('conversations/{id}/messages/{messageId}', [MessageController::class, 'destroy']);

    // Plans
    Route::get('plans', [PlanController::class, 'index']);
    Route::get('plans/{id}', [PlanController::class, 'show']);
    Route::post('plans', [PlanController::class, 'store']);
    Route::put('plans/{id}', [PlanController::class, 'update']);
    Route::delete('plans/{id}', [PlanController::class, 'destroy']);

    // Subscriptions
    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::get('subscriptions/{id}', [SubscriptionController::class, 'show']);
    Route::post('subscriptions', [SubscriptionController::class, 'store']);
    Route::put('subscriptions/{id}', [SubscriptionController::class, 'update']);
    Route::delete('subscriptions/{id}', [SubscriptionController::class, 'destroy']);

    // Languages
    Route::get('languages', [LanguageController::class, 'index']);
    Route::get('languages/{locale}', [LanguageController::class, 'show']);
    Route::post('languages/set', [LanguageController::class, 'set']);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
