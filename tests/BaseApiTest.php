<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Базовый класс для интеграционных API-тестов.
 *
 * Настраивает HTTP-клиент и предоставляет хелпер для запросов.
 */
abstract class BaseApiTest extends TestCase
{
    protected Client $client;
    protected static string $baseUri;
    protected static array $auth;

    public static function setUpBeforeClass(): void
    {
        // Базовый URI и креды можно переопределить через окружение
        self::$baseUri = getenv('BASE_URI') ?: 'https://go.dev-01.ru/api/v1/';
        self::$auth    = [
            getenv('API_USER') ?: 'realuser',
            getenv('API_PASS') ?: 'truestrongpassword'
        ];
    }

    protected function setUp(): void
    {
        // Инициализация Guzzle-клиента
        $this->client = new Client([
            'base_uri'    => self::$baseUri,
            'auth'        => self::$auth,
            'http_errors' => false,
            'headers'     => ['Content-Type' => 'application/json'],
        ]);
    }

    /**
     * Выполнить HTTP-запрос к API.
     *
     * @param string $method HTTP-метод (GET, POST и т.д.)
     * @param string $path   Путь относительно base_uri (например, 'account/register')
     * @param array  $json   Данные для JSON-тела запроса
     * @return ResponseInterface
     */
    protected function request(string $method, string $path, array $json = []): ResponseInterface
    {
        return $this->client->request($method, $path, ['json' => $json]);
    }
}
