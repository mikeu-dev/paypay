<?php

namespace App\Filament\Resources\Allowances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AllowanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('value')
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
