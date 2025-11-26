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
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\TenantController;
use App\Http\Controllers\API\AdminPaymentController;
use App\Http\Controllers\API\AdminDashboardController;
use App\Http\Controllers\API\AdminAgentController;
use App\Http\Controllers\API\AdminDisputeController;
use App\Http\Controllers\API\AdminPlanController;

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

// Admin Routes (Super Admin only)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Dashboard
    Route::get('dashboard/overview', [AdminDashboardController::class, 'overview']);
    Route::get('dashboard/analytics', [AdminDashboardController::class, 'analytics']);
    Route::get('dashboard/recent-activity', [AdminDashboardController::class, 'recentActivity']);
    Route::get('dashboard/tenants', [AdminDashboardController::class, 'tenantOverview']);

    // User Management
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('users/{id}/assign-role', [UserController::class, 'assignRole']);
    Route::post('users/{id}/remove-role', [UserController::class, 'removeRole']);

    // Role Management
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
    Route::post('roles/{id}/assign-permission', [RoleController::class, 'assignPermission']);
    Route::post('roles/{id}/remove-permission', [RoleController::class, 'removePermission']);

    // Permission Management
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::post('permissions', [PermissionController::class, 'store']);
    Route::get('permissions/{id}', [PermissionController::class, 'show']);
    Route::put('permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy']);

    // Tenant Management
    Route::get('tenants', [TenantController::class, 'index']);
    Route::post('tenants', [TenantController::class, 'store']);
    Route::get('tenants/{id}', [TenantController::class, 'show']);
    Route::put('tenants/{id}', [TenantController::class, 'update']);
    Route::delete('tenants/{id}', [TenantController::class, 'destroy']);
    Route::get('tenants/{id}/stats', [TenantController::class, 'stats']);

    // Payment Management
    Route::get('payments', [AdminPaymentController::class, 'index']);
    Route::get('payments/{id}', [AdminPaymentController::class, 'show']);
    Route::put('payments/{id}', [AdminPaymentController::class, 'update']);
    Route::get('payments/stats', [AdminPaymentController::class, 'stats']);

    // Agent Management
    Route::get('agents', [AdminAgentController::class, 'index']);
    Route::get('agents/{id}', [AdminAgentController::class, 'show']);
    Route::put('agents/{id}', [AdminAgentController::class, 'update']);
    Route::delete('agents/{id}', [AdminAgentController::class, 'destroy']);
    Route::post('agents/{id}/verify', [AdminAgentController::class, 'verify']);
    Route::post('agents/{id}/unverify', [AdminAgentController::class, 'unverify']);
    Route::get('agents/stats', [AdminAgentController::class, 'stats']);

    // Dispute Management
    Route::get('disputes', [AdminDisputeController::class, 'index']);
    Route::get('disputes/{id}', [AdminDisputeController::class, 'show']);
    Route::put('disputes/{id}', [AdminDisputeController::class, 'update']);
    Route::post('disputes/{id}/assign', [AdminDisputeController::class, 'assignToAdmin']);
    Route::post('disputes/bulk-update', [AdminDisputeController::class, 'bulkUpdate']);
    Route::get('disputes/stats', [AdminDisputeController::class, 'stats']);

    // Plan Management
    Route::get('plans', [AdminPlanController::class, 'index']);
    Route::post('plans', [AdminPlanController::class, 'store']);
    Route::get('plans/{id}', [AdminPlanController::class, 'show']);
    Route::put('plans/{id}', [AdminPlanController::class, 'update']);
    Route::delete('plans/{id}', [AdminPlanController::class, 'destroy']);
    Route::post('plans/{id}/toggle', [AdminPlanController::class, 'toggleActive']);
    Route::post('plans/{id}/duplicate', [AdminPlanController::class, 'duplicate']);
    Route::get('plans/stats', [AdminPlanController::class, 'stats']);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
