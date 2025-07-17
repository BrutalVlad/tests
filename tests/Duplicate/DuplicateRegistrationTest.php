<?php

namespace Tests\Duplicate;

use Tests\BaseApiTest;
use PHPUnit\Framework\Attributes\Test;

class DuplicateRegistrationTest extends BaseApiTest
{
    #[Test]
    public function testDuplicateEmailReturnsError(): void
    {
        $payload = [
            'name'         => 'Иван',
            'patronymic'   => 'Иванович',
            'surname'      => 'Иванов',
            'phone'        => '+79210000000',
            'email'        => 'duplicate@example.com', // уже занятый email
            'address'      => 'г. Москва, ул. Ленина, д.1',
            'refovod_code' => '334',
            'password'     => 'somestrongpass',
        ];

        $response = $this->request('POST', 'account/register', $payload);
        $body = (string)$response->getBody();

        // Ожидаем код ошибки (400 или 409, уточни по API)
        $this->assertGreaterThanOrEqual(
            400,
            $response->getStatusCode(),
            "Expected error status code. Got {$response->getStatusCode()}. Body:\n$body"
        );

        $data = json_decode($body, true);

        $this->assertArrayHasKey('reason', $data, 'Response missing "reason" key');
        $this->assertStringContainsString(
            'email',
            strtolower($data['reason']),
            'Expected error message to mention "email"'
        );
    }
}
