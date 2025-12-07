<?php

namespace App\Filament\Resources\PayrollAllowances;

use App\Filament\Resources\PayrollAllowances\Pages\CreatePayrollAllowance;
use App\Filament\Resources\PayrollAllowances\Pages\EditPayrollAllowance;
use App\Filament\Resources\PayrollAllowances\Pages\ListPayrollAllowances;
use App\Filament\Resources\PayrollAllowances\Pages\ViewPayrollAllowance;
use App\Filament\Resources\PayrollAllowances\Schemas\PayrollAllowanceForm;
use App\Filament\Resources\PayrollAllowances\Schemas\PayrollAllowanceInfolist;
use App\Filament\Resources\PayrollAllowances\Tables\PayrollAllowancesTable;
use App\Models\PayrollAllowance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollAllowanceResource extends Resource
{
    protected static ?string $model = PayrollAllowance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = '';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PayrollAllowanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayrollAllowanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollAllowancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayrollAllowances::route('/'),
            'create' => CreatePayrollAllowance::route('/create'),
            'view' => ViewPayrollAllowance::route('/{record}'),
            'edit' => EditPayrollAllowance::route('/{record}/edit'),
        ];
    }
}
