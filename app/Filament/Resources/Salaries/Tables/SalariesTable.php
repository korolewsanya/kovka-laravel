<?php

namespace App\Filament\Resources\Salaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('employee.full_name')
                    ->label('Сотрудник')
                    ->searchable()
                    ->sortable(),

                // ДОЛЖНОСТЬ ПОДТЯГИВАЕТСЯ ИЗ employees
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Редактировать'),
                DeleteAction::make()->label('Удалить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
