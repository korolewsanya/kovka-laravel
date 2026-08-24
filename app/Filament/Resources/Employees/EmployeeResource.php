<?php

namespace App\Filament\Resources\Employees;

use App\Models\Employee;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Сотрудники';

    protected static ?string $modelLabel = 'Сотрудник';

    protected static ?string $pluralModelLabel = 'Сотрудники';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getNavigationItems(): array
    {
        if (auth()->user()->role !== 'admin') {
            return [];
        }
        return parent::getNavigationItems();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('ФИО')
                    ->required()
                    ->maxLength(255),

                TextInput::make('position')
                    ->label('Должность')
                    ->maxLength(255)
                    ->nullable(),

                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->maxLength(255)
                    ->nullable(),

                TextInput::make('email')
                    ->label('Email для входа')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Новый пароль')
                    ->password()
                    ->nullable()
                    ->minLength(6)
                    ->maxLength(255)
                    ->revealable()
                    ->placeholder('Оставьте пустым, чтобы не менять')
                    ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null),

                Textarea::make('address')
                    ->label('Адрес')
                    ->nullable()
                    ->rows(2),

                DatePicker::make('hire_date')
                    ->label('Дата приема')
                    ->nullable(),

                Textarea::make('notes')
                    ->label('Примечания')
                    ->nullable()
                    ->rows(3),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        User::create([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'employee',
        ]);

        unset($data['email']);
        unset($data['password']);

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['password'])) {
            $employee = Employee::find($data['id']);
            if ($employee && $employee->email) {
                User::where('email', $employee->email)->update([
                    'password' => bcrypt($data['password'])
                ]);
            }
        }

        unset($data['password']);
        return $data;
    }

    public static function mutateFormDataBeforeDelete(array $data): array
    {
        $employee = Employee::find($data['id']);
        if ($employee && $employee->email) {
            User::where('email', $employee->email)->delete();
        }
        return $data;
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

                TextColumn::make('position')
                    ->label('Должность')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),

                TextColumn::make('hire_date')
                    ->label('Дата приема')
                    ->date('d.m.Y')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make()
                    ->label('Редактировать'),
                
                // Сброс пароля
                \Filament\Actions\Action::make('resetPassword')
                    ->label('Сбросить пароль')
                    ->color('warning')
                    ->icon('heroicon-o-key')
                    ->action(function ($record) {
                        // Генерируем случайный пароль из 8 символов
                        $newPassword = \Illuminate\Support\Str::random(8);

                        // Обновляем пароль в users
                        \App\Models\User::where('email', $record->email)->update([
                        'password' => bcrypt($newPassword)
                        ]);

                        // Показываем уведомление с новым паролем
                        \Filament\Notifications\Notification::make()
                    ->title('Пароль сброшен!')
                    ->body("Новый пароль: {$newPassword}")
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->modalHeading('Сброс пароля')
            ->modalDescription('Вы уверены, что хотите сбросить пароль сотрудника? Новый пароль будет сгенерирован автоматически и показан после подтверждения в правой верхней части экрана.')
            ->modalSubmitActionLabel('Да, сбросить'),

            DeleteAction::make()
                ->label('Удалить'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Employees\Pages\ManageEmployees::route('/'),
        ];
    }
}