<?php

namespace App\Filament\Resources\PayrollAllowances\Pages;

use App\Filament\Resources\PayrollAllowances\PayrollAllowanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayrollAllowances extends ListRecords
{
    protected static string $resource = PayrollAllowanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
