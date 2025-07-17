# API Integration Tests

Набор интеграционных автотестов для метода POST /account/register API сервиса GoDev-01.

# Установка

## Клонировать репозиторий
git clone https://github.com/BrutalVlad/tests.git

cd tests

## Установить зависимости через Composer
composer install

# Запуск тестов

## Запуск всех интеграционных тестов
vendor/bin/phpunit

--debug выводит подробный лог и тело ответа API.

# Особенности

## Тест демонстрирует:

реальный HTTP-вызов к API с Basic Auth;
проверку HTTP-статусов; 

## Коментарий для тех-лида
В коде файлов присутствуют комментарии, файлы разделены на каждый тест свой файл. 
Можно вызывать проверки как по отдельности (vendor/bin/phpunit --testsuite Duplicate/Positive/ValidationErrors), так и разом через vendor/bin/phpunit 

В тестах tests\ValidationErrors\InvalidFormatTest реализована проверка валидации некорректных входных данных (email, телефон, пароль). 
Ожидалось, что API вернёт статус 400. Однако, API вернул 201, что означает успешную регистрацию - что есть баг. 
