<?php

namespace App\Filament\Employee\Resources\Payslips\Pages;

use App\Filament\Employee\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\ListRecords;

class ListPayslips extends ListRecords
{
    protected static string $resource = PayslipResource::class;
}
