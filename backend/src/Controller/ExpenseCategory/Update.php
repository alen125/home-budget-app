<?php

declare(strict_types=1);

namespace App\Controller\ExpenseCategory;

use App\DTO\ExpenseCategory\ExpenseCategoryDTO;
use App\Entity\ExpenseCategory;
use App\Service\ExpenseCategory\ExpenseCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense-categories/{id}', methods: [Request::METHOD_PUT])]
class Update extends AbstractController
{
    public function __invoke(
        ExpenseCategory $expenseCategory,
        #[MapRequestPayload]
        ExpenseCategoryDTO $expenseCategoryDTO,
        ExpenseCategoryHandler $handler,
    ): JsonResponse {
        return $this->json(
            data: $handler->update($expenseCategory, $expenseCategoryDTO),
            status: Response::HTTP_OK,
            context: [
                'groups' => ['expenseCategory:single'],
            ],
        );
    }
}
