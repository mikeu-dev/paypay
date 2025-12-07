<?php

namespace App\Filament\Resources\PayrollDeductions;

use App\Filament\Resources\PayrollDeductions\Pages\CreatePayrollDeduction;
use App\Filament\Resources\PayrollDeductions\Pages\EditPayrollDeduction;
use App\Filament\Resources\PayrollDeductions\Pages\ListPayrollDeductions;
use App\Filament\Resources\PayrollDeductions\Pages\ViewPayrollDeduction;
use App\Filament\Resources\PayrollDeductions\Schemas\PayrollDeductionForm;
use App\Filament\Resources\PayrollDeductions\Schemas\PayrollDeductionInfolist;
use App\Filament\Resources\PayrollDeductions\Tables\PayrollDeductionsTable;
use App\Models\PayrollDeduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollDeductionResource extends Resource
{
    protected static ?string $model = PayrollDeduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = '';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PayrollDeductionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayrollDeductionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollDeductionsTable::configure($table);
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
            'index' => ListPayrollDeductions::route('/'),
            'create' => CreatePayrollDeduction::route('/create'),
            'view' => ViewPayrollDeduction::route('/{record}'),
            'edit' => EditPayrollDeduction::route('/{record}/edit'),
        ];
    }
}
