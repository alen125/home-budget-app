<?php

declare(strict_types=1);

namespace App\Controller\ExpenseCategory;

use App\Entity\ExpenseCategory;
use App\Service\ExpenseCategory\ExpenseCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense-categories/{id}', methods: [Request::METHOD_DELETE])]
class Delete extends AbstractController
{
    public function __invoke(
        ExpenseCategory $expenseCategory,
        ExpenseCategoryHandler $handler,
    ): JsonResponse {
        $handler->delete($expenseCategory);
        return $this->json(
            data: [],
            status: Response::HTTP_OK,
        );
    }
}
