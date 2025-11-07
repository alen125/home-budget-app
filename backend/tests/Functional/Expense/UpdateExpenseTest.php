<?php

declare(strict_types=1);

namespace App\Tests\Functional\Expense;

use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Entity\User;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateExpenseTest extends AbstractWebTestCase
{
    public function testSuccessUpdateExpense(): void
    {
        $testUser = $this->getOneEntityBy(User::class, ['email' => 'test@example.com']);
        $expense = $this->getOneEntityBy(Expense::class, ['owner' => $testUser->getId()]);
        $newCategory = $this->getOneEntityBy(ExpenseCategory::class, ['name' => 'Travel']);
        $payload = [
            'amount' => 99.99,
            'categoryId' => $newCategory->getId(),
            'description' => 'Updated expense',
        ];
        $data = $this->makeRequest(
            Request::METHOD_PUT,
            '/api/expenses/' . $expense->getId(),
            Response::HTTP_OK,
            $payload
        );
        $this->assertEquals($payload['amount'], $data['amount'] ?? null);
        $this->assertEquals($payload['categoryId'], $data['category']['id'] ?? null);
        $this->assertEquals($payload['description'], $data['description'] ?? null);
    }

    public function testUpdateExpenseValidation(): void
    {
        $expense = $this->getOneEntityBy(Expense::class, []);
        $payload = [
            'amount' => -10,
            'categoryId' => null,
            'description' => '',
        ];
        $data = $this->makeRequest(
            Request::METHOD_PUT,
            '/api/expenses/' . $expense->getId(),
            Response::HTTP_BAD_REQUEST,
            $payload
        );
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Validation failed', $data['error']);
        $this->assertArrayHasKey('details', $data);
        $this->assertIsArray($data['details']);
    }
}

