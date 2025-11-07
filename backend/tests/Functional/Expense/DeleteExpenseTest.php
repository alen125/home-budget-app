<?php

declare(strict_types=1);

namespace App\Tests\Functional\Expense;

use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteExpenseTest extends AbstractWebTestCase
{
    public function testSuccessDeleteExpense(): void
    {
        $expense = $this->getOneEntityBy(Expense::class, []);
        $data = $this->makeRequest(
            Request::METHOD_DELETE,
            '/api/expenses/' . $expense->getId(),
            Response::HTTP_OK
        );
        $this->assertEquals([], $data);
    }

    public function testDeleteNonExistentExpense(): void
    {
        $data = $this->makeRequest(
            Request::METHOD_DELETE,
            '/api/expenses/999999',
            Response::HTTP_NOT_FOUND
        );
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Entity not found!', $data['error']);
    }
}

