# 🚀 ПРОЕКТ-ПОРТФОЛИО: "КОВКА" — Интернет-магазин кованых изделий

![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Laravel](https://img.shields.io/badge/Laravel-13-red)
![Filament](https://img.shields.io/badge/Filament-5-purple)
![Tailwind](https://img.shields.io/badge/Tailwind-3-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
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

<br>

#### ⚙️ Админ-панель и CRM (Filament)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты админки</summary>
<br>

| Дашборд | Управление заказами |
| :---: | :---: |
| <img src="screenshots/Админка/дашборд.png" width="400"> | <img src="screenshots/Админка/заказы.png" width="400"> |
| **CRM система** | **Финансы** |
| <img src="screenshots/Админка/crm.png" width="400"> | <img src="screenshots/Админка/финансы.png" width="400"> |

</details>

<br>

#### 📱 Мобильное приложение (Android/Java)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты мобильного приложения</summary>
<br>

| Экран входа | Список заказов | Уведомления |
| :---: | :---: | :---: |
| <img src="screenshots/Мобильное/login.png" width="200"> | <img src="screenshots/Мобильное/orders.png" width="200"> | <img src="screenshots/Мобильное/notifications.png" width="200"> |

</details>

---

### 🛠️ Технологии:

| Компонент | Технологии |
|-----------|------------|
| **Backend** | Laravel 13, Filament 5, Livewire 4, Sanctum |
| **Frontend** | Tailwind CSS, Vite |
| **Mobile** | Java (Android), Retrofit, REST API |
| **Сервер** | Herd (PHP 8.2, MySQL) |

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
