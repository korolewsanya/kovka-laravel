<?php

namespace App\Filament\Resources\Products;

use App\Models\Product;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\ViewField;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Товары';
    protected static ?string $modelLabel = 'Товар';
    protected static ?string $pluralModelLabel = 'Товары';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
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
                    ->downloadable(),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('image')
                    ->label('Фото')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        if ($record && $record->image) {
                            return url('/storage/products/' . $record->image);
                        }
                        return url('/images/placeholder.png');
                    }),

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Категория')
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
                    ->label('Активен')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Категория')
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
                    ]),
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
            'index' => \App\Filament\Resources\Products\Pages\ListProducts::route('/'),
            'create' => \App\Filament\Resources\Products\Pages\CreateProduct::route('/create'),
            'edit' => \App\Filament\Resources\Products\Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}