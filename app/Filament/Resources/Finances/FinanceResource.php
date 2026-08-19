<?php

namespace App\Filament\Resources\Finances;

use App\Models\Finance;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinanceResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'Финансы';

    protected static ?string $modelLabel = 'Финансовая запись';

    protected static ?string $pluralModelLabel = 'Финансы';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label('Дата')
                    ->nullable(),
                
                TextInput::make('income')
                    ->label('Доход (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->default(0),
                
                TextInput::make('expense')
                    ->label('Расход (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->default(0),
                
                TextInput::make('profit')
                    ->label('Прибыль (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->default(0),
                
                Textarea::make('note')
                    ->label('Примечание')
                    ->nullable()
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('date')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable(),
                
                TextColumn::make('income')
                    ->label('Доход')
                    ->money('RUB')
                    ->sortable(),
                
                TextColumn::make('expense')
                    ->label('Расход')
                    ->money('RUB')
                    ->sortable(),
                
                TextColumn::make('profit')
                    ->label('Прибыль')
                    ->money('RUB')
                    ->sortable()
                    ->color(fn ($record): string => $record->profit < 0 ? 'danger' : 'success'),
                
                TextColumn::make('note')
                    ->label('Примечание')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Редактировать'),
                DeleteAction::make()
                    ->label('Удалить'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Finances\Pages\ManageFinances::route('/'),
        ];
    }
    public static function getNavigationItems(): array
    {
        if (auth()->user()->role !== 'admin') {
        return [];
        }
        return parent::getNavigationItems();
    }
}