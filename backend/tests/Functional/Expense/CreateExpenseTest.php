<?php

declare(strict_types=1);

namespace App\Tests\Functional\Expense;

use App\Entity\ExpenseCategory;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateExpenseTest extends AbstractWebTestCase
{
    public function testSuccessCreateExpense(): void
    {
        $payload = [
            'amount' => 50.75,
            'categoryId' => $this->getOneEntityBy(ExpenseCategory::class, ['name' => 'Travel'])->getId(),
            'description' => 'Test expense creation',
        ];

        $data = $this->makeRequest(
            Request::METHOD_POST,
            '/api/expenses',
            Response::HTTP_CREATED,
            $payload
        );

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals($payload['amount'], $data['amount'] ?? null);
        $this->assertEquals($payload['categoryId'], $data['category']['id'] ?? null);
        $this->assertEquals($payload['description'], $data['description'] ?? null);
    }

    #[DataProvider('expenseInvalidDataProvider')]
    public function testCreateExpenseValidation(array $payload, array $expectedErrors): void
    {
        $data = $this->makeRequest(
            Request::METHOD_POST,
            '/api/expenses',
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
                    ['field' => 'amount', 'message' => 'This value should not be null.'],
                    ['field' => 'categoryId', 'message' => 'This value should not be null.'],
                    ['field' => 'description', 'message' => 'This value should not be blank.'],
                ],
            ],
            'negative amount' => [
                ['amount' => -5, 'categoryId' => 2],
                [
                    ['field' => 'amount', 'message' => 'This value should be positive.'],
                ],
            ],
            'category does not exist' => [
                ['amount' => 10, 'categoryId' => 99999],
                [
                    ['field' => 'categoryId', 'message' => 'Category not found!'],
                ],
            ],
        ];
    }
}