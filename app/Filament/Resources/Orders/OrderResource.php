<?php

namespace App\Filament\Resources\Orders;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Заказы';
    protected static ?string $modelLabel = 'Заказ';
    protected static ?string $pluralModelLabel = 'Заказы';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Товар')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(100)
                    ->nullable(),

                TextInput::make('customer_name')
                    ->label('Имя клиента')
                    ->required()
                    ->maxLength(255),

                TextInput::make('customer_phone')
                    ->label('Телефон')
                    ->required()
                    ->maxLength(255),

                TextInput::make('customer_email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->nullable(),

                Textarea::make('comment')
                    ->label('Комментарий')
                    ->nullable()
                    ->rows(3),

                DatePicker::make('order_date')
                    ->label('Дата заказа')
                    ->nullable(),

                TextInput::make('price')
                    ->label('Цена (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->nullable(),

                TextInput::make('paid')
                    ->label('Оплачено (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->default(0),

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

                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'in_progress' => 'В работе',
                        'done' => 'Выполнен',
                        'cancelled' => 'Отменен',
                    ])
                    ->default('new')
                    ->required(),

                Textarea::make('progress')
                    ->label('Ход работы')
                    ->nullable()
                    ->rows(3),

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('№ заказа')
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Фото')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        if ($record && $record->image) {
                            return url('/storage/products/' . $record->image);
                        }
                        return url('/images/placeholder.png');
                    }),

                TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Клиент')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_phone')
                    ->label('Телефон')
                    ->searchable(),

                TextColumn::make('order_date')
                    ->label('Дата заказа')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),

                TextColumn::make('paid')
                    ->label('Оплачено')
                    ->money('RUB')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Новый',
                        'in_progress' => 'В работе',
                        'done' => 'Выполнен',
                        'cancelled' => 'Отменен',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'in_progress' => 'warning',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'in_progress' => 'В работе',
                        'done' => 'Выполнен',
                        'cancelled' => 'Отменен',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Редактировать'),
                DeleteAction::make()
                    ->label('Удалить')
                    ->visible(fn () => auth()->user()->role === 'admin'),
            ])
            ->headerActions([
                // Добавляем текст-подсказку
                Action::make('info')
                ->label('Перед тестированием рекомендуется сбросить данные до исходных значений')
                ->color('gray')
                ->disabled()
                ->icon('heroicon-o-information-circle'),

                Action::make('resetDemo')
                    ->label('Сбросить демо-данные')
                    ->color('danger')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->modalHeading('Сброс демо-данных')
                    ->modalDescription('Внимание! Все изменения будут потеряны. Данные вернутся к исходному состоянию.')
                    ->modalSubmitActionLabel('Да, сбросить')
                    ->action(function () {
                        try {
                            Artisan::call('demo:reset');
                            Notification::make()
                                ->title('✅ Демо-данные восстановлены!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('❌ Ошибка при сбросе данных!')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Orders\Pages\ManageOrders::route('/'),
        ];
    }
}