<?php

declare(strict_types=1);

namespace App\Tests\Functional\ExpenseCategory;

use App\Entity\ExpenseCategory;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteExpenseCategoryTest extends AbstractWebTestCase
{
    public function testSuccessDeleteExpenseCategory(): void
    {
        $category = $this->getOneEntityBy(ExpenseCategory::class, []);
        $data = $this->makeRequest(
            Request::METHOD_DELETE,
            '/api/expense-categories/' . $category->getId(),
            Response::HTTP_OK
        );
        $this->assertEquals([], $data);
    }

    public function testDeleteNonExistentExpenseCategory(): void
    {
        $data = $this->makeRequest(
            Request::METHOD_DELETE,
            '/api/expense-categories/999999',
            Response::HTTP_NOT_FOUND
        );
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Entity not found!', $data['error']);
    }
}

