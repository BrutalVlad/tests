<?php

namespace Tests\ValidationErrors;

use Tests\BaseApiTest;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * ValidationErrors/InvalidFormatTest
 *
 * Интеграционные тесты для сценариев некорректного формата полей.
 */
class InvalidFormatTest extends BaseApiTest
{
    /**
     * Тестирование некорректного формата email
     */
    public function testInvalidEmailFormat(): void
    {
        $payload = [
            'name'         => 'Иван',
            'patronymic'   => 'Иванович',
            'surname'      => 'Иванов',
            'phone'        => '+79217512021',
            'email'        => 'invalid'. rand(1, 10000) .'-email-format',  // нет @
            'address'      => 'г. Москва, ул. Ленина, д.1',
            'refovod_code' => '334',
            'password'     => 'somestrongpass'
        ];

        $response = $this->request('POST', 'account/register', $payload);
        $body = (string) $response->getBody();

        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "Expected 400 for invalid email format. Body:\n" . $body
        );

        $data = json_decode($body, true);
        $this->assertArrayHasKey(
            'reason',
            $data,
            "Response does not contain 'reason' for invalid email. Body:\n" . $body
        );
        $this->assertStringContainsString(
            'email',
            mb_strtolower($data['reason']),
            "Error message should mention 'email'. Body:\n" . $body
        );
    }

    /**
     * Тестирование некорректного формата телефона
     */
    public function testInvalidPhoneFormat(): void
    {
        $payload = [
            'name'         => 'Иван',
            'patronymic'   => 'Иванович',
            'surname'      => 'Иванов',
            'phone'        => 'ABC12345',  // не цифры
            'email'        => 'user'. rand(1, 10000) .'@example.com',
            'address'      => 'г. Москва',
            'refovod_code' => '334',
            'password'     => 'somestrongpass'
        ];

        $response = $this->request('POST', 'account/register', $payload);
        $body = (string) $response->getBody();

        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "Expected 400 for invalid phone format. Body:\n" . $body
        );

        $data = json_decode($body, true);
        $this->assertArrayHasKey(
            'reason',
            $data,
            "Response does not contain 'reason' for invalid phone. Body:\n" . $body
        );
        $this->assertStringContainsString(
            'phone',
            mb_strtolower($data['reason']),
            "Error message should mention 'phone'. Body:\n" . $body
        );
    }

    /**
     * Тестирование слишком короткого пароля
     */
    public function testShortPassword(): void
    {
        $payload = [
            'name'         => 'Иван',
            'patronymic'   => 'Иванович',
            'surname'      => 'Иванов',
            'phone'        => '+79217512021',
            'email'        => 'user' . rand(1,1000) . '@example.com',
            'address'      => 'г. Москва',
            'refovod_code' => '334',
            'password'     => '123'  // меньше 6
        ];

        $response = $this->request('POST', 'account/register', $payload);
        $body = (string) $response->getBody();

        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "Expected 400 for short password. Body:\n" . $body
        );

        $data = json_decode($body, true);
        $this->assertArrayHasKey(
            'reason',
            $data,
            "Response does not contain 'reason' for short password. Body:\n" . $body
        );
        $this->assertStringContainsString(
            'пароль',
            mb_strtolower($data['reason']),
            "Error message should mention 'пароль'. Body:\n" . $body
        );
    }
}
