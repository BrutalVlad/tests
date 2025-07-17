<?php

namespace Tests\Positive;

use Tests\BaseApiTest;

/**
 * Positive/Tests for successful registration through API
 */
class RegisterSuccessTest extends BaseApiTest
{
    public function testSuccessfulRegistration(): void
    {
        $payload = [
            'name'         => 'Иван',
            'patronymic'   => 'Иванович',
            'surname'      => 'Иванов',
            'phone'        => '+79217512021',
            'email'        => 'ivanov' . rand(1, 10000) . '@example.com',
            'address'      => 'г. Москва, ул. Ленина, д.1',
            'refovod_code' => '334',
            'password'     => 'somestrongpass',
        ];

        $response = $this->request('POST', 'account/register', $payload);
        $body = (string) $response->getBody();
        echo "\n=== RESPONSE BODY ===\n" . $body . "\n=====================\n";

        // Проверка статуса 201
        $this->assertEquals(
            201,
            $response->getStatusCode(),
            "Unexpected status code. Response body:\n" . $body
        );

        // Проверка структуры JSON
        $data = json_decode($body, true);
        $this->assertArrayHasKey(
            'login_link',
            $data,
            "Response does not contain login_link:\n" . $body
        );
    }
}
