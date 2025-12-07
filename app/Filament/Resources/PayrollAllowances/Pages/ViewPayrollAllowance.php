<?php

namespace App\Filament\Resources\PayrollAllowances\Pages;

use App\Filament\Resources\PayrollAllowances\PayrollAllowanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPayrollAllowance extends ViewRecord
{
    protected static string $resource = PayrollAllowanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
