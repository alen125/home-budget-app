<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Service\Expense\ExpenseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses/{id}', methods: [Request::METHOD_DELETE])]
class Delete extends AbstractController
{
    public function __invoke(
        Expense $expense,
        ExpenseHandler $handler,
    ): JsonResponse {
        $handler->delete($expense);
        return $this->json(
            data: [],
            status: Response::HTTP_OK,
            context: [
                'groups' => ['expense:single'],
            ],
        );
    }
}
