<?php

namespace App\Filament\Employee\Resources\Payslips;

use App\Filament\Employee\Resources\Payslips\Pages;
use App\Models\Payroll;
use BackedEnum;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;

class PayslipResource extends Resource
{
    protected static ?string $model = Payroll::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationLabel = 'My Payslips';
    protected static ?string $pluralLabel = 'Payslips';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('employee_id', auth()->user()->employee?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                 // Read only view
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('period_start')->date()->label('Start Date'),
                Tables\Columns\TextColumn::make('period_end')->date()->label('End Date'),
                Tables\Columns\TextColumn::make('net_salary')->money('IDR')->label('Net Salary'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Generated At'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Payroll $record) => route('payroll.payslip', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayslips::route('/'),
        ];
    }
}
