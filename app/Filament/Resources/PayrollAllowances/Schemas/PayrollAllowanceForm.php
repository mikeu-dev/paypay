<?php

namespace App\Filament\Resources\PayrollAllowances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollAllowanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('payroll_id')
                    ->relationship('payroll', 'total_allowances')
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
