<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CustomLogin extends BaseLogin
{
    /**
     * Переопределяем форму входа, чтобы добавить обязательный чекбокс.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),

                // === ДОБАВЛЯЕМ ЧЕКБОКС СОГЛАСИЯ ===
                Checkbox::make('agree_rules')
                    // С помощью HtmlString мы можем сделать текст кликабельной ссылкой
                    ->label(new HtmlString(
                        'Соглашаюсь с <a href="' . url('/privacy') . '" target="_blank" style="color: #f59e0b; text-decoration: underline;">политикой конфиденциальности</a> и правилами тестирования'
                    ))
                    ->required() // Поле обязательно для заполнения
                    ->accepted() // Галочка должна быть установлена (значение true/on)
                    ->validationMessages([
                        'accepted' => 'Для входа в систему необходимо согласие с правилами тестирования.',
                    ]),
            ]);
    }
}
