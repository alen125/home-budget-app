<?php

declare(strict_types=1);

namespace App\Tests\Functional\ExpenseCategory;

use App\Tests\Functional\Abstraction\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetExpenseCategoryTest extends AbstractWebTestCase
{
    public function testGetExpenses(): void
    {
        $data = $this->makeRequest(Request::METHOD_GET, '/api/expense-categories', Response::HTTP_OK);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('data', $data);
        $this->assertGreaterThan(0, count($data['data']));
        $this->assertArrayHasKey('name', $data['data'][0]);
    }
}