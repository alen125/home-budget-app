<?php

declare(strict_types=1);

namespace App\Controller\ExpenseCategory;

use App\DTO\ExpenseCategory\ExpenseCategoryDTO;
use App\Service\ExpenseCategory\ExpenseCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense-categories', methods: [Request::METHOD_POST])]
class Create extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload]
        ExpenseCategoryDTO $expenseCategoryDTO,
        ExpenseCategoryHandler $handler,
    ): JsonResponse {
        return $this->json(
            data: $handler->create($expenseCategoryDTO),
            status: Response::HTTP_CREATED,
            context: [
                'groups' => ['expenseCategory:single'],
            ],
        );
    }
}
