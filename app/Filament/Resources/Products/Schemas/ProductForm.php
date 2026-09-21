<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),

                Select::make('category')
                    ->label('Категория')
                    ->required()
                    ->options([
                        'vorota' => 'Ворота',
                        'zabor' => 'Заборы',
                        'mangal' => 'Мангалы',
                        'kozirek' => 'Козырьки',
                        'lavo4ki' => 'Лавочки',
                        'ogradki' => 'Оградки',
                        'reshetki' => 'Решетки',
                        'mebel' => 'Мебель',
                        'melo4i' => 'Полезные мелочи',
                        'other' => 'Другое',
                    ])
                    ->searchable(),

                //ПОЛЕ ДЛЯ ЗАГРУЗКИ ИЗОБРАЖЕНИЯ
                FileUpload::make('image')
                    ->label('Изображение')
                    ->image()
                    ->directory('products')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->nullable()
                    ->imagePreviewHeight('500')
                    ->loadingIndicatorPosition('center')
                    ->openable()
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                            $extension = $file->getClientOriginalExtension();
                            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            // Убираем все проблемные символы: пробелы, скобки, и т.д.
                            $name = preg_replace('/[^A-Za-zА-Яа-я0-9_-]/', '_', $name);
                            return $name . '_' . time() . '.' . $extension;
                        }),

                TextInput::make('price')
                    ->label('Цена (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->nullable(),

                TextInput::make('length')
                    ->label('Длина (мм)')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('width')
                    ->label('Ширина (мм)')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('height')
                    ->label('Высота (мм)')
                    ->nullable()
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),

            ]);
    }
}
