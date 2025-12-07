<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayrollInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee_id')
                    ->numeric(),
                TextEntry::make('period_start')
                    ->date(),
                TextEntry::make('period_end')
                    ->date(),
                TextEntry::make('basic_salary')
                    ->numeric(),
                TextEntry::make('total_allowances')
                    ->numeric(),
                TextEntry::make('total_deductions')
                    ->numeric(),
                TextEntry::make('net_salary')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
