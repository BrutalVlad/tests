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
vendor/bin/phpunit --debug

--debug выводит подробный лог и тело ответа API.

# Особенности

## Тест демонстрирует:

реальный HTTP-вызов к API с Basic Auth;

проверку HTTP-статуса и структуры JSON;

вывод тела ответа при падении теста для отладки; 

в коде файла RegisterApiTest.php присутствуют комментарии. 
