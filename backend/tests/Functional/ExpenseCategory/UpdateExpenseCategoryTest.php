<?php

declare(strict_types=1);

namespace App\Tests\Functional\ExpenseCategory;

use App\Entity\ExpenseCategory;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateExpenseCategoryTest extends AbstractWebTestCase
{
    public function testSuccessUpdateExpenseCategory(): void
    {
        $category = $this->getOneEntityBy(ExpenseCategory::class, []);
        $payload = [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ];
        $data = $this->makeRequest(
            Request::METHOD_PUT,
            '/api/expense-categories/' . $category->getId(),
            Response::HTTP_OK,
            $payload
        );
        $this->assertEquals($payload['name'], $data['name'] ?? null);
    }

    public function testUpdateExpenseCategoryValidation(): void
    {
        $category = $this->getOneEntityBy(ExpenseCategory::class, []);
        $payload = [
            'name' => '',
            'description' => '',
        ];
        $data = $this->makeRequest(
            Request::METHOD_PUT,
            '/api/expense-categories/' . $category->getId(),
            Response::HTTP_BAD_REQUEST,
            $payload
        );
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Validation failed', $data['error']);
        $this->assertArrayHasKey('details', $data);
        $this->assertIsArray($data['details']);
    }
}

