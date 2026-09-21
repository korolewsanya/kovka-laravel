<?php

namespace App\Filament\Resources\Salaries;

use App\Models\Salary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Salaries\Schemas\SalaryForm;
use App\Filament\Resources\Salaries\Tables\SalariesTable;

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
        return SalaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalariesTable::configure($table);
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
