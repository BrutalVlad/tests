<?php

namespace Tests\ValidationErrors;

use Tests\BaseApiTest;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * ValidationErrors/MissingFieldsTest
 *
 * Интеграционные тесты для сценария отсутствия обязательных полей.
 */
class MissingFieldsTest extends BaseApiTest
{
    #[DataProvider('missingFieldsProvider')]
    public function testMissingFieldReturns400(string $field, array $payload, string $expectedField): void
    {
        // Выполняем запрос без одного из обязательных полей
        $response = $this->request('POST', 'account/register', $payload);
        $body = (string) $response->getBody();

        // Ожидаем код 400 Bad Request
        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "Expected 400 when missing field '$field'. Response body:\n" . $body
        );

        // Парсим JSON и проверяем наличие reason
        $data = json_decode($body, true);
        $this->assertArrayHasKey(
            'reason',
            $data,
            "Response for missing '$field' does not contain 'reason':\n" . $body
        );

        // Проверяем, что сообщение упоминает преобразованное имя поля (camelCase)
        $this->assertStringContainsString(
            $expectedField,
            $data['reason'],
            "Error message should mention missing field '$expectedField':\n" . $body
        );
    }

    public static function missingFieldsProvider(): array
    {
        $base = [
            'name'         => 'Иван',
            'patronymic'   => 'Иванович',
            'surname'      => 'Иванов',
            'phone'        => '+79217512021',
            'email'        => 'test' . rand(1,1000) . '@example.com',
            'address'      => 'г. Москва, ул. Ленина, д.1',
            'refovod_code' => '334',
            'password'     => 'somestrongpass',
        ];

        $cases = [];
        foreach (['name', 'surname', 'phone', 'email', 'address', 'refovod_code', 'password'] as $field) {
            $payload = $base;
            unset($payload[$field]);
            // преобразуем имя поля в camelCase для сравнения с ответом
            $expected = preg_replace_callback('/_([a-z])/', fn($m) => strtoupper($m[1]), $field);
            $cases["missing_{$field}"] = [$field, $payload, $expected];
        }

        return $cases;
    }
}
