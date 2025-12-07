<?php

namespace App\Filament\Resources\PayrollAllowances\Pages;

use App\Filament\Resources\PayrollAllowances\PayrollAllowanceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPayrollAllowance extends EditRecord
{
    protected static string $resource = PayrollAllowanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
