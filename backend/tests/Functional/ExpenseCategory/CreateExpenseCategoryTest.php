<?php

declare(strict_types=1);

namespace App\Tests\Functional\ExpenseCategory;

use App\Entity\ExpenseCategory;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateExpenseCategoryTest extends AbstractWebTestCase
{
    public function testSuccessCreateExpense(): void
    {
        $payload = ['name' => 'Test expense creation'];
        $data = $this->makeRequest(
            Request::METHOD_POST,
            '/api/expense-categories',
            Response::HTTP_CREATED,
            $payload
        );
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals($payload['name'], $data['name'] ?? null);
    }

    #[DataProvider('expenseInvalidDataProvider')]
    public function testCreateExpenseValidation(array $payload, array $expectedErrors): void
    {
        $data = $this->makeRequest(
            Request::METHOD_POST,
            '/api/expense-categories',
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
            'missing field' => [
                [],
                [
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                ],
            ],
            'null field' => [
                ['name' => null],
                [
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                ],
            ],
            'min length' => [
                ['name' => 'a'],
                [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'max length' => [
                [
                    'name' => 'testtestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestest'
                        . 'testtestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestest'
                        . 'testtestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestesttesttestest'
                ],
                [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
        ];
    }


}