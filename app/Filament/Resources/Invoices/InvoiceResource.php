<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\Tables\InvoicesTable;
use App\Models\Invoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'title')
                            ->searchable(),
                        Forms\Components\TextInput::make('number')
                            ->default('INV-' . date('Ymd') . '-' . rand(1000, 9999))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                                'void' => 'Void',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->default(now())
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->default(now()->addDays(14))
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('description')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $set('amount', $state * $get('unit_price'));
                                    }),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $set('amount', $state * $get('qty'));
                                    }),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->readOnly()
                                    ->dehydrated()
                                    ->disabled(),
                            ])
                            ->columns(5)
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::updateTotals($get, $set);
                            }),
                    ]),

                \Filament\Schemas\Components\Section::make('Totals')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->readOnly()
                            ->prefix('IDR'),
                        Forms\Components\TextInput::make('tax_rate')
                            ->numeric()
                            ->default(0)
                            ->suffix('%')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::updateTotals($get, $set);
                            }),
                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->readOnly()
                            ->prefix('IDR'),
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->readOnly()
                            ->prefix('IDR'),
                    ])->columns(4),
            ]);
    }

    public static function updateTotals(Forms\Get $get, Forms\Set $set): void
    {
        $items = $get('items');
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += floatval($item['amount'] ?? 0);
        }

        $taxRate = floatval($get('tax_rate'));
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $set('subtotal', $subtotal);
        $set('tax_amount', $taxAmount);
        $set('total', $total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'paid' => 'success',
                        'void' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Invoice $record) => route('invoices.print', $record))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('send')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->action(function (Invoice $record) {
                        $record->client->notify(new \App\Notifications\InvoiceSent($record));
                        $record->update(['status' => 'sent']);
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Invoice Sent to Client')
                            ->send();
                    }),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
