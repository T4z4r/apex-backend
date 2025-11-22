<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:landlord,tenant,agent'],
        ]);

        $tenantId = 1; // Default for non-landlords

        // Create tenant for landlords
        if ($request->role === 'landlord') {
            $tenant = Tenant::create([
                'name' => $request->name . "'s Property Management",
                'domain' => Str::slug($request->name) . '-' . Str::random(4),
            ]);

            // Create free trial subscription (Professional plan)
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => 2, // Professional plan
                'billing_cycle' => 'monthly',
                'trial_ends_at' => now()->addDays(30), // 30-day free trial
                'status' => 'trial',
            ]);

            $tenantId = $tenant->id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenantId,
        ]);

        // Assign role to user - ensure role exists first
        $role = Role::firstOrCreate(['name' => $request->role]);
        $user->assignRole($role);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
