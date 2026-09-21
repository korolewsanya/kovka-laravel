<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                // колонка с изображением (изображение полное)
                ImageColumn::make('image')
                    ->label('Фото')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        if ($record && $record->image) {
                            return url('/storage/products/' . $record->image);
                        }
                        return url('/images/placeholder.png');
                    })
                    ->extraImgAttributes([
                        'style' => 'background-color: white; object-fit: contain;',
                    ]),

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Категория')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'vorota' => 'Ворота',
                        'zabor' => 'Заборы',
                        'mangal' => 'Мангалы',
                        'kozirek' => 'Козырьки',
                        'lavo4ki' => 'Лавочки',
                        'ogradki' => 'Оградки',
                        'reshetki' => 'Решетки',
                        'mebel' => 'Мебель',
                        'melo4i' => 'Полезные мелочи',
                        default => $state,
                    }),

                TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),

                TextColumn::make('length')
                    ->label('Длина')
                    ->toggleable(),

                TextColumn::make('width')
                    ->label('Ширина')
                    ->toggleable(),

                TextColumn::make('height')
                    ->label('Высота')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Наличие')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Редактировать'),
                DeleteAction::make()
                    ->label('Удалить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
