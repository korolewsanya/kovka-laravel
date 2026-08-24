<?php

namespace App\Filament\Resources\Materials;

use App\Models\Material;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class MaterialResource extends Resource
{
    //Связываем ресурс с моделью Material, чтобы работать с таблицей материалы
    protected static ?string $model = Material::class;

    //Иконка, которая будет отображаться в боковом меню админки рядом с пунктом «Материалы»
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube; //иконка кубика из Heroicons

    //Название пункта меню в боковой панели администратора
    protected static ?string $navigationLabel = 'Материалы';

    //Название одной записи в единственном числе.Используется в интерфейсе (заголовки, кнопки, сообщения)
    protected static ?string $modelLabel = 'Материал';

    //Название во множественном числе. Используется для заголовков списков.
    protected static ?string $pluralModelLabel = 'Материалы';

    //Отображения названия записи в разных местах: заголовке, выпадающих списках, хлебных крошках
    protected static ?string $recordTitleAttribute = 'name';

    //Определяем, какие поля (компоненты) будут в форме создания и редактирования материалов.
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label('Дата')
                    ->nullable(),
                
                TextInput::make('name')
                    ->label('Название материала')
                    ->required() //обязательно для заполнения
                    ->maxLength(255),
                
                TextInput::make('purchased')
                    ->label('Куплено')
                    ->numeric() //принимает только числа
                    ->prefix('шт.')
                    ->default(0),
                
                TextInput::make('used')
                    ->label('Израсходовано')
                    ->numeric()
                    ->prefix('шт.')
                    ->default(0),
                
                TextInput::make('balance')
                    ->label('Остаток')
                    ->numeric()
                    ->prefix('шт.')
                    ->default(0),
                
                TextInput::make('price_per_unit')
                    ->label('Цена за ед. (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->default(0),
                
                TextInput::make('total_price')
                    ->label('Итого (руб.)')
                    ->numeric()
                    ->prefix('₽')
                    ->default(0),
            ]);
    }

    //Определяем, как будет выглядеть таблица со списком материалов.
    public static function table(Table $table): Table
    {
        //Добавляем колонки в таблицу
        return $table
            ->columns([
                //Показываем колонку id из таблицы materials (как текст)
                TextColumn::make('id') 
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true), //колонка скрыта по умолчанию, но пользователь может ее включить через настройки таблицы
                
                TextColumn::make('date')
                    ->label('Дата') //заголовок колонки
                    ->date('d.m.Y') //форматирует дату как "24.07.2026" (день.месяц.год)
                    ->sortable(),   //можно сортировать по этой колонке (клик на заголовок)
                
                TextColumn::make('name')
                    ->label('Материал')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('purchased')
                    ->label('Куплено')
                    ->numeric(0)
                    ->sortable(),
                
                TextColumn::make('used')
                    ->label('Израсходовано')
                    ->numeric(0)
                    ->sortable(),
                
                TextColumn::make('balance')
                    ->label('Остаток')
                    ->numeric(0)
                    ->sortable()
                    //Если остаток (balance) меньше 0 → красный цвет (danger)
                    //Если остаток ≥ 0 → зеленый цвет (success)
                    //$record — текущая запись из базы данных
                    ->color(fn ($record): string => $record->balance < 0 ? 'danger' : 'success'),
                
                TextColumn::make('price_per_unit')
                    ->label('Цена за ед.')
                    ->money('RUB')
                    ->sortable(),
                
                TextColumn::make('total_price')
                    ->label('Итого')
                    ->money('RUB')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            //Действия, доступные для каждой записи в таблице
            ->recordActions([
                EditAction::make()
                    ->label('Редактировать'),  // Кнопка "Редактировать"
                DeleteAction::make()
                    ->label('Удалить')  // Кнопка "Удалить"
                    ->visible(fn () => auth()->user()->role === 'admin'),  // ТОЛЬКО АДМИН       
            ]);
    }

    //Метод, определяющий маршруты (URL) для вашего ресурса.
    public static function getPages(): array
    {
        //'index' — название страницы (главная страница ресурса)
        //ManageMaterials::route('/') — класс страницы, которая обрабатывает маршрут / внутри этого ресурса
        return [
            'index' => \App\Filament\Resources\Materials\Pages\ManageMaterials::route('/'),
        ];
    }
}