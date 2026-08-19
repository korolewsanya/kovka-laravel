<?php

namespace App\Filament\Resources\Salaries;

use App\Models\Salary;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'Зарплаты';
    protected static ?string $modelLabel = 'Зарплата';
    protected static ?string $pluralModelLabel = 'Зарплаты';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')
                ->label('Сотрудник')
                ->relationship('employee', 'full_name')
                ->searchable()
                ->preload()
                ->required(),

            // ДОЛЖНОСТЬ ТОЛЬКО ДЛЯ ОТОБРАЖЕНИЯ (НЕ ДЛЯ ВВОДА)
            // Убираем это поле из формы, так как должность берётся из employees

            DatePicker::make('date')
                ->label('Дата')
                ->nullable(),

            TextInput::make('accrued')
                ->label('Начислено (руб.)')
                ->numeric()
                ->prefix('₽')
                ->default(0),

            TextInput::make('received')
                ->label('Получено (руб.)')
                ->numeric()
                ->prefix('₽')
                ->default(0),

            TextInput::make('description')
                ->label('Описание')
                ->maxLength(255)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('ID')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('employee.full_name')
                ->label('Сотрудник')
                ->searchable()
                ->sortable(),

            //ДОЛЖНОСТЬ ПОДТЯГИВАЕТСЯ ИЗ employees 
            TextColumn::make('employee.position')
                ->label('Должность')
                ->searchable()
                ->sortable(),

            TextColumn::make('date')
                ->label('Дата')
                ->date('d.m.Y')
                ->sortable(),

            TextColumn::make('accrued')
                ->label('Начислено')
                ->money('RUB')
                ->sortable(),

            TextColumn::make('received')
                ->label('Получено')
                ->money('RUB')
                ->sortable(),

            TextColumn::make('description')
                ->label('Описание')
                ->limit(30),
        ])->recordActions([
            EditAction::make()->label('Редактировать'),
            DeleteAction::make()->label('Удалить'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Salaries\Pages\ListSalaries::route('/'),
            'create' => \App\Filament\Resources\Salaries\Pages\CreateSalary::route('/create'),
            'edit' => \App\Filament\Resources\Salaries\Pages\EditSalary::route('/{record}/edit'),
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