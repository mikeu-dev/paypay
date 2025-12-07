<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use App\Filament\Resources\AttendanceResource\Widgets\AttendanceCalendarWidget;
use BackedEnum;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string | \UnitEnum | null $navigationGroup = 'Attendance';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Clock In'),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Clock Out'),
                Forms\Components\Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'absent' => 'Absent',
                        'permission' => 'Permission',
                    ])
                    ->default('present')
                    ->required(),
                Forms\Components\TextInput::make('fingerprint')
                    ->label('Fingerprint ID'),
                Forms\Components\TextInput::make('coordinate')
                    ->label('GPS Coordinate'),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'absent' => 'danger',
                        'permission' => 'info',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            AttendanceCalendarWidget::class,
        ];
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
