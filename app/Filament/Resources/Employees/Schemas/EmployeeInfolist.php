<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee_code'),
                TextEntry::make('name'),
                TextEntry::make('position'),
                TextEntry::make('department')
                    ->placeholder('-'),
                TextEntry::make('hire_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('base_salary')
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
