<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->getCurrentTenantId();

        if ($tenantId) {
            $builder->where('tenant_id', $tenantId);
        }
    }

    /**
     * Get the current tenant ID from authenticated user or session.
     */
    private function getCurrentTenantId()
    {
        if (Auth::check()) {
            return Auth::user()->tenant_id;
        }

        // Fallback to session or other methods
        return session('tenant_id');
    }
}
