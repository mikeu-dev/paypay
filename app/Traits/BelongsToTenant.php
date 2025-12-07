<?php

namespace App\Traits;

use App\Models\Team;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        // Auto-assign team_id on creation if set in Filamanet context
        static::creating(function ($model) {
            if (!$model->team_id) {
                try {
                    $tenant = \Filament\Facades\Filament::getTenant();
                    if ($tenant) {
                        $model->team_id = $tenant->id;
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
