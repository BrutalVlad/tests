<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * tests/RegisterApiTest.php
 *
 * Интеграционный тест регистрации пользователя через REST API.
 * Демонстрирует:
 *   – вызов реального HTTP‑эндпоинта (Guzzle);
 *   – проверку статуса ответа и структуры JSON;
 *   – вывод тела ответа для отладки при падении.
 */
class RegisterApiTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        // Инициализация HTTP‑клиента с базовым URI и Basic Auth
        $this->client = new Client([
            'base_uri'    => 'https://go.dev-01.ru/api/v1/',
            'auth'        => ['realuser', 'truestrongpassword'],
            'http_errors' => false,                   // чтобы Guzzle не бросал исключения на 4xx/5xx
            'headers'     => ['Content-Type' => 'application/json'],
        ]);
    }

    public function testSuccessfulRegistration(): void
    {
        // 1) Отправляем POST-запрос на /account/register
        $response = $this->client->post('account/register', [
            'json' => [
                'name'         => 'Иван',                       // обязательное поле name
                'patronymic'   => 'Иванович',                  // поле patronymic
                'surname'      => 'Иванов',                    // обязательное поле surname
                'phone'        => '+79217512021',              // валидный формат телефона
                'email'        => 'ivanov' . rand(1,1000) . '@example.com',
                'address'      => 'г. Москва, ул. Ленина, д. 1',
                'refovod_code' => '334',                       // существующий код нотариуса
                'password'     => 'somestrongpass',            // пароль ≥ 6 символов
            ]
        ]);

        // 2) Перехват тела ответа для логирования
        $body = (string) $response->getBody();
        echo "\n=== RESPONSE BODY ===\n", $body, "\n=====================\n";

        // 3) Проверка HTTP‑статуса 201 Created
        $this->assertEquals(
            201,
            $response->getStatusCode(),
            "Неправильный статус при регистрации. Тело ответа:\n" . $body
        );

        // 4) Декодирование JSON и проверка наличия ключа login_link
        $data = json_decode($body, true);
        $this->assertArrayHasKey(
            'login_link',
            $data,
            "В ответе отсутствует login_link:\n" . $body
        );
    }
}
