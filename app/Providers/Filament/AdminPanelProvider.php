<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()     //Устанавливает эту панель как основную (по умолчанию), т.к. может быть несколько панелей (админская, пользовательская), эта — главная.
            ->id('admin')   //Присваивает панели уникальный идентификатор 'admin'. Для внутренней маршрутизации и конфигурации.
            ->topNavigation() //это для верхнего меню (по умолчанию сбоку слева)
            ->path('admin') //Устанавливает URL-адрес админ-панели. Теперь админка доступна по адресу: http://ваш-сайт/admin
            ->login()       //Включает стандартную страницу входа в админку. По адресу /admin/login появляется форма логина.
            ->colors([      //Устанавливает основной цвет панели.
                'primary' => Color::Amber,
            ])
            // Автоматически находит и подключает все ресурсы (CRUD-интерфейсы). Путь: app/Filament/Pages  Пространство имен: App\Filament\Pages
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([               //регистрирует дополнительные страницы
                Dashboard::class,
            ])
            //Автоматически находит все виджеты для дашборда. Путь: app/Filament/Widgets  Пространство имен: App\Filament\Widgets
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            //Явно подключает стандартные виджеты Filament
            ->widgets([
                AccountWidget::class,       //показывает информацию о текущем пользователе
                //FilamentInfoWidget::class,  //показывает версию Filament
            ])
            //проверки, которые выполняются при каждом запросе к админке.
            ->middleware([
                EncryptCookies::class,              // Шифрование кук
                AddQueuedCookiesToResponse::class,  // Добавление кук в ответ
                StartSession::class,                // Запуск сессии
                AuthenticateSession::class,         // Проверка аутентификации сессии
                ShareErrorsFromSession::class,      // Передача ошибок из сессии
                PreventRequestForgery::class,       // Защита от CSRF-атак
                SubstituteBindings::class,          // Подстановка маршрутных привязок
                DisableBladeIconComponents::class,  // Отключает стандартные иконки Blade
                DispatchServingFilamentEvent::class,// Запускает событие загрузки Filament
            ])
            //Требует аутентификацию для доступа к админке.
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandLogo(asset('images/logo2.png'))
            ->darkModeBrandLogo(asset('images/logo.png'))  // второй логотип для тёмной темы
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/favicon.ico'))
            ->brandName('Ковка')
            ->darkMode(true)//ВКЛЮЧАЕМ ТЁМНУЮ ТЕМУ
            ->viteTheme('resources/css/filament/admin/theme.css');

    }
}
