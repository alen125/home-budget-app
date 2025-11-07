<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\DTO\Expense\ExpenseDTO;
use App\Entity\Expense;
use App\Entity\User;
use App\Service\Expense\ExpenseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses/{id}', methods: [Request::METHOD_PUT])]
class Update extends AbstractController
{
    public function __invoke(
        Expense $expense,
        #[MapRequestPayload]
        ExpenseDTO $expenseDTO,
        ExpenseHandler $handler,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json(
            data: $handler->update($expense, $user, $expenseDTO),
            status: Response::HTTP_OK,
            context: [
                'groups' => ['expense:single'],
            ],
        );
    }
}
