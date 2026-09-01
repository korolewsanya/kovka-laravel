<?php

namespace App\Filament\Resources\WorkReports;

use App\Models\WorkReport;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class WorkReportResource extends Resource
{
    protected static ?string $model = WorkReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Отчеты о работе';
    protected static ?string $modelLabel = 'Отчет';
    protected static ?string $pluralModelLabel = 'Отчеты';
    protected static ?string $recordTitleAttribute = 'id';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role !== 'admin') {
            $query->where(function ($q) {
                $q->where('employee_id', auth()->user()->id);
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Сотрудник')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Textarea::make('task')
                    ->label('ТЗ (Техническое задание)')
                    ->nullable()
                    ->rows(3),

                Textarea::make('report')
                    ->label('Отчет')
                    ->nullable()
                    ->rows(3),

                DatePicker::make('date')
                    ->label('Дата')
                    ->nullable(),

                // поле для загрузки изображения
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
                    ->helperText('Загрузите изображение к отчету (jpg, png, gif)')
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        // Сохраняем ТОЛЬКО имя файла (без products/)
                        return $file->getClientOriginalName();
                    }) ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                // ИМЯ ПОДТЯГИВАЕТСЯ ИЗ employees
                TextColumn::make('employee.full_name')
                    ->label('Сотрудник')
                    ->searchable()
                    ->sortable(),

                // ДОЛЖНОСТЬ ПОДТЯГИВАЕТСЯ ИЗ employees
                TextColumn::make('employee.position')
                    ->label('Должность')
                    ->searchable()
                    ->sortable(),

                // колонка с изображением (изображение не полное)
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
                        'class' => 'bg-white',
                    ]),

                TextColumn::make('date')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('task')
                    ->label('ТЗ')
                    ->limit(30),

                TextColumn::make('report')
                    ->label('Отчет')
                    ->limit(30),
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
            'index' => \App\Filament\Resources\WorkReports\Pages\ManageWorkReports::route('/'),
        ];
    }
}
