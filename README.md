# Home Portal
SPA-приложение на Laravel 8 и Vue 3 CLI. Основная цель - учёт бытовых вопросов таких как: Списки задач, контроль финансов, напоминания, списки покупок и т.д.

## Установка проекта

Запускаем команды:
```
git clone git@github.com:c0ntr-all/laravel-vue-home-portal.git
```
```
composer install
```
Настраиваем `.env` файл под базу данных.

## Подготовка данных
Подготавливаем таблицы проекта в базе данных:
```
php artisan migrate
```

Наполняем таблицы фейковыми данными:
```
php artisan db:seed
```

## Установка Frontend
Идем в папку с фронтендом:
```
cd frontend
```
Устанавливаем пакеты и запускаем сервер для работы
```
npm install && npm run dev
```

Тестового пользователя можно взять из таблицы Users (желательно с id 1).

Пароль для входа у всех пользователей - `password`
