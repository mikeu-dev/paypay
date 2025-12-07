<?php

namespace App\Filament\Exports;

use App\Models\Payroll;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PayrollExporter extends Exporter
{
    protected static ?string $model = Payroll::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('employee.name')->label('Employee'),
            ExportColumn::make('period_start'),
            ExportColumn::make('period_end'),
            ExportColumn::make('basic_salary'),
            ExportColumn::make('total_allowances'),
            ExportColumn::make('total_deductions'),
            ExportColumn::make('net_salary'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your payroll export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
