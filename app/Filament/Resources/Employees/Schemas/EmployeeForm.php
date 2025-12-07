<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('position')
                    ->required(),
                TextInput::make('department')
                    ->default(null),
                DatePicker::make('hire_date'),
                TextInput::make('base_salary')
                    ->required()
                    ->numeric(),

                \Filament\Schemas\Components\Section::make('User Account')
                    ->description('Create a login account for this employee.')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique('users', 'email')
                            ->visibleOn('create'),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->visibleOn('create'),
                    ])
                    ->visibleOn('create'),
            ]);
    }
}
