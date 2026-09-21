<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Сотрудник')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),

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
}
