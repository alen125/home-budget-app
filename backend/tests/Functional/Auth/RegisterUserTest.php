<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Entity\User;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserTest extends AbstractWebTestCase
{
    public function testSuccessRegisterUser(): void
    {
        $payload = [
            'email' => 'newuser_' . uniqid() . '@example.com',
            'password' => 'test123!',
            'name' => 'Test User',
        ];
        $data = $this->makeRequest(
            Request::METHOD_POST,
            '/api/register',
            Response::HTTP_CREATED,
            $payload
        );
        $this->assertEquals($payload['email'], $data['email'] ?? null);
        $this->assertEquals($payload['name'], $data['name'] ?? null);
        $this->assertArrayNotHasKey('password', $data);
    }

    #[DataProvider('expenseInvalidDataProvider')]
    public function testRegisterValidationErrors(array $payload, array $expectedErrors): void
    {
        $data = $this->makeRequest(
            Request::METHOD_POST,
            '/api/register',
            Response::HTTP_BAD_REQUEST,
            $payload
        );

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Validation failed', $data['error']);

        $this->assertArrayHasKey('details', $data);
        $this->assertIsArray($data['details']);

        foreach ($expectedErrors as $expectedError) {
            $this->assertContains($expectedError, $data['details']);
        }
    }

    public static function expenseInvalidDataProvider(): array
    {
        return [
            'all fields missing' => [
                [],
                [
                    ['field' => 'email', 'message' => 'This value should not be blank.'],
                    ['field' => 'password', 'message' => 'This value should not be blank.'],
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                ],
            ],
            'invalid email' => [
                ['email' => 'foo', 'password' => 'test123!', 'name' => 'TestName'],
                [
                    ['field' => 'email', 'message' => 'This value is not a valid email address.'],
                ],
            ],
            'short password' => [
                ['email' => 'test@email.com', 'password' => 'test', 'name' => 'TestName'],
                [
                    ['field' => 'password', 'message' => 'This value is too short. It should have 6 characters or more.'],
                ],
            ],
            'short name' => [
                ['email' => 'test@email.com', 'password' => 'test123!', 'name' => 'A'],
                [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
        ];
    }
}

