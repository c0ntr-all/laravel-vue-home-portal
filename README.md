# Home Portal
SPA-приложение на Laravel 8 и Vue 3 CLI. Основная цель - учёт бытовых вопросов таких как: Списки задач, контроль финансов, напоминания, списки покупок и т.д.

## Установка проекта

Запускаем команды:
```shell
git clone git@github.com:c0ntr-all/laravel-vue-home-portal.git
```
```shell
composer install
```
Настраиваем `.env` файл под базу данных.

## Подготовка данных
Подготавливаем таблицы проекта в базе данных:
```shell
php artisan migrate
```

Наполняем таблицы фейковыми данными:
```shell
php artisan db:seed
```

## Установка Frontend
### element plus 1.3.0-beta.1 (Устаревшее):
```shell
cd frontend
```
Устанавливаем пакеты и запускаем сервер для работы
```shell
npm install && npm run dev
```

### quasar 2.12.0 (Актуальное):
```shell
cd client
```
Устанавливаем пакеты и запускаем сервер для работы
```shell
npm install && quasar dev
```

Билд
```shell
quasar build
```
В папке `client/dist/spa` создаем файл `.htaccess` со следующим содержимым:
```apacheconf
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
```
Это нужно, чтобы маршрутизацией spa занимался quasar, а не apache.

## Настройка веб-сервера
Делаем так, чтобы домены смотрели в эти папки:
- home-portal.test -> public
- home-portal.prod -> client/dist/spa
- api.home-portal.prod -> public

Также, делаем алиас localhost для home-portal.test

## Пользователь
Тестового пользователя можно взять из таблицы Users (желательно с id 1).

Пароль для входа у всех пользователей - `password`
