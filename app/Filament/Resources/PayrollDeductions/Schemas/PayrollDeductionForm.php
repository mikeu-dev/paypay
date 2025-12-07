<?php

namespace App\Filament\Resources\PayrollDeductions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollDeductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('payroll_id')
                    ->relationship('payroll', 'total_deductions')
                    ->searchable()
                    ->preload()
                    ->loadingMessage('Loading...'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
            ]);
    }
}
