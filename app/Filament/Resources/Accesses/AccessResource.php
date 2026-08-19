<?php

namespace App\Filament\Resources\Access;

use App\Models\Access;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccessResource extends Resource
{
    protected static ?string $model = Access::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Права доступа';

    protected static ?string $modelLabel = 'Право доступа';

    protected static ?string $pluralModelLabel = 'Права доступа';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('ФИО')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('profession')
                    ->label('Профессия')
                    ->maxLength(255)
                    ->nullable(),
                
                TextInput::make('work_class')
                    ->label('Класс работы')
                    ->numeric()
                    ->nullable(),
                
                TextInput::make('access_code')
                    ->label('Код доступа')
                    ->required()
                    ->unique()
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
                
                TextColumn::make('full_name')
                    ->label('ФИО')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('profession')
                    ->label('Профессия')
                    ->searchable(),
                
                TextColumn::make('work_class')
                    ->label('Класс работы')
                    ->sortable(),
                
                TextColumn::make('access_code')
                    ->label('Код доступа')
                    ->searchable()
                    ->copyable(),
                
                IconColumn::make('is_active')
                    ->label('Активен')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Access\Pages\ManageAccess::route('/'),
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