# 🚀 ПРОЕКТ-ПОРТФОЛИО: "КОВКА" — Интернет-магазин кованых изделий

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6?logo=livewire&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-5-FDAE4B?logo=laravel&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind-3-06B6D4?logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?logo=alpinedotjs&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![REST API](https://img.shields.io/badge/REST_API-25A162?logo=fastapi&logoColor=white)
![Java](https://img.shields.io/badge/Java-Android-ED8B00?logo=openjdk&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

### 📊 Статус проекта:
✅ **Демонстрационный сервер** — проект развернут и доступен онлайн  
✅ **Google Play** — мобильное приложение опубликовано для портфолио  
✅ **Полный функционал** — все модули работают (CRM, API, админка)  
✅ **Готов к внедрению** — может быть адаптирован для реального бизнеса

---

### 🎯 Что сделано:
- Полноценный интернет-магазин (каталог, заказы, корзина)
- Админ-панель + CRM для управления бизнесом (Filament 5)
- Мобильное приложение-админка на Java
- REST API для синхронизации всех частей
- Полная CRM система (сотрудники, финансы, материалы)

---

### 📸 Галерея скриншотов

#### 🛍 Витрина интернет-магазина (Frontend)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты сайта</summary>
<br>

| Главная страница | Каталог товаров |
| :---: | :---: |
| <img src="screenshots/Сайт/главная.png" width="400"> | <img src="screenshots/Сайт/товары.png" width="400"> |
| **Поиск по сайту** | **Оформление заказа** |
| <img src="screenshots/Сайт/поиск.png" width="400"> | <img src="screenshots/Сайт/заказ.png" width="400"> |
| **Успешный заказ** | |
| <img src="screenshots/Сайт/финиш.png" width="400"> | |

</details>


#### ⚙️ Админ-панель и CRM (Filament)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты админки</summary>
<br>

| Инфопанель (Dashboard) | Управление заказами |
| :---: | :---: |
| <img src="screenshots/Админка/Инфопанель.png" width="400"> | <img src="screenshots/Админка/Заказы.png" width="400"> |
| **Редактирование заказа** | **Управление товарами** |
| <img src="screenshots/Админка/ЗаказыРедактирование.png" width="400"> | <img src="screenshots/Админка/Товары.png" width="400"> |
| **Редактирование товара** | **Материалы** |
| <img src="screenshots/Админка/ТоварыРедактирование.png" width="400"> | <img src="screenshots/Админка/Материалы.png" width="400"> |
| **Отчеты** | |
| <img src="screenshots/Админка/Отчеты.png" width="400"> | 

</details>


#### 📱 Мобильное приложение (Android/Java)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты приложения</summary>
<br>

| Главная | Заказы | Материалы |
| :---: | :---: | :---: |
| <img src="screenshots/Приложение/Главная.png" width="200"> | <img src="screenshots/Приложение/Заказы.png" width="200"> | <img src="screenshots/Приложение/Материалы.png" width="200"> |
| **Редактирование заказа** | **Создание товара** | **Редактирование материала** |
| <img src="screenshots/Приложение/ЗаказыРедактирование.png" width="200"> | <img src="screenshots/Приложение/ТоварыСоздание.png" width="200"> | <img src="screenshots/Приложение/МатериалыРедактирование.png" width="200"> |
| **Отчеты** | **Редактирование отчета** | **Финансы** |
| <img src="screenshots/Приложение/Отчеты.png" width="200"> | <img src="screenshots/Приложение/ОтчетыРедактирование.png" width="200"> | <img src="screenshots/Приложение/Финансы.png" width="200"> |

</details>

</details>

---

### 🛠️ Технологии:

| Компонент | Технологии |
|-----------|------------|
| **Backend** | Laravel 13, Filament 5, Livewire 4, Sanctum |
| **Frontend** | Tailwind CSS, Vite |
| **Mobile** | Java (Android), Retrofit, REST API |
| **Сервер** | PHP 8.4, MySQL |

---

### 📁 Структура проекта

```
kovka-laravel/
├── app/
│   ├── Filament/          # Админ-панель + CRM (ресурсы, виджеты, страницы)
│   ├── Http/              # Контроллеры (API и Web)
│   ├── Models/            # Модели Eloquent
│   ├── Policies/          # Права доступа
│   └── Notifications/     # Уведомления
├── config/                # Конфигурации Laravel
├── database/
│   ├── migrations/        # Миграции БД
│   └── seeders/           # Наполнение тестовыми данными
├── routes/
│   ├── api.php            # REST API маршруты
│   ├── web.php            # Web маршруты
│   └── console.php        # Консольные команды
├── resources/
│   ├── views/             # Blade-шаблоны
│   ├── lang/              # Локализация (ru)
│   └── css/               # Стили
└── public/                # Публичные файлы (изображения, сборки)
```

### 🔗 Демо и ссылки
- 🌐 **Сайт:** [ваш-домен.ru]
- 📱 **Google Play:** [ссылка на приложение]
- 📂 **GitHub:** [https://github.com/korolewsanya/kovka-laravel](https://github.com/korolewsanya/kovka-laravel)

---

### 📌 О проекте

> Проект разработан как портфолио-решение для демонстрации навыков разработки и внедрения. Может быть адаптирован под реальные задачи бизнеса.

---
