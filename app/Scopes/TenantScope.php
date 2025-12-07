<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Filament\Facades\Filament;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // For now, relies on Filament's tenancy context.
        // In API context, we'll need to ensure Filament::getTenant() returns the correct team.
        try {
            $tenant = Filament::getTenant();

            if ($tenant) {
                $builder->where('team_id', $tenant->id);
            }
        } catch (\Exception $e) {
            // Filament context might not be available (e.g., seeding, console)
            // In these cases, we typically do NOT scope, or we assume specific context.
            // For safety, let's assume if no tenant context, we don't scope (allow all)
            // OR we scope to nothing. Decisions...
            // Standard approach: if running in console/seeding, we might want all data.
        }
    }
}
