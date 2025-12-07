<?php

namespace App\Filament\Resources\PayrollDeductions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayrollDeductionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('payroll_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('amount')
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
