<?php

namespace App\Filament\Resources\PayrollDeductions\Pages;

use App\Filament\Resources\PayrollDeductions\PayrollDeductionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPayrollDeduction extends ViewRecord
{
    protected static string $resource = PayrollDeductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
