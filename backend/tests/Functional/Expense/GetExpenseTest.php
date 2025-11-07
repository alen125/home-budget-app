<?php

declare(strict_types=1);

namespace App\Tests\Functional\Expense;

use App\Entity\ExpenseCategory;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetExpenseTest extends AbstractWebTestCase
{
    public function testGetExpenses(): void
    {
        $data = $this->makeRequest(Request::METHOD_GET, '/api/expenses', Response::HTTP_OK);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('data', $data);
        $this->assertGreaterThan(0, count($data['data']));
        $this->assertArrayHasKey('amount', $data['data'][0]);
        $this->assertArrayHasKey('description', $data['data'][0]);
        $this->assertArrayHasKey('category', $data['data'][0]);
        $this->assertArrayHasKey('name', $data['data'][0]['category']);
    }

    public function testGetExpensesPriceFilters(): void
    {
        $minPrice = 20;
        $maxPrice = 50;

        $data = $this->makeRequest(
            Request::METHOD_GET,
            \sprintf('/api/expenses?priceMin=%d&priceMax=%d', $minPrice, $maxPrice),
            Response::HTTP_OK
        );

        foreach ($data['data'] as $expense) {
            $this->assertGreaterThanOrEqual($minPrice, $expense['amount']);
            $this->assertLessThanOrEqual($maxPrice, $expense['amount']);
        }
    }

    public function testGetExpensesCategoryFilters(): void
    {
        /** @var ExpenseCategory $expenseCategory */
        $expenseCategory = $this->getOneEntityBy(ExpenseCategory::class, ['name' => 'Groceries']);

        $data = $this->makeRequest(
            Request::METHOD_GET,
            \sprintf('/api/expenses?categoryId=%d', $expenseCategory->getId()),
            Response::HTTP_OK
        );

        foreach ($data['data'] as $expense) {
            $this->assertEquals($expenseCategory->getName(), $expense['category']['name']);
        }
    }
}