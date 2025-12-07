<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('generate')
                ->label('Generate Payroll')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('period_start')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('period_end')
                        ->required()
                        ->afterOrEqual('period_start'),
                ])
                ->action(function (array $data) {
                    \App\Jobs\GeneratePayrollJob::dispatch($data['period_start'], $data['period_end']);
                    
                    \Filament\Notifications\Notification::make()
                        ->title("Payroll generation started in background")
                        ->success()
                        ->send();
                }),

            \Filament\Actions\ExportAction::make()
                ->exporter(\App\Filament\Exports\PayrollExporter::class),
            CreateAction::make(),
        ];
    }
}
