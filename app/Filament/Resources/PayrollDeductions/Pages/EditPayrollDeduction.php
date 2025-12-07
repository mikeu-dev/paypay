<?php

namespace App\Filament\Resources\PayrollDeductions\Pages;

use App\Filament\Resources\PayrollDeductions\PayrollDeductionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPayrollDeduction extends EditRecord
{
    protected static string $resource = PayrollDeductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
