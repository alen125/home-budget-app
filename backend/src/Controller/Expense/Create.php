<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\DTO\Expense\ExpenseDTO;
use App\Entity\User;
use App\Service\Expense\ExpenseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses', methods: [Request::METHOD_POST])]
class Create extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload]
        ExpenseDTO $expenseDTO,
        ExpenseHandler $handler,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json(
            data: $handler->create($user, $expenseDTO),
            status: Response::HTTP_CREATED,
            context: [
                'groups' => ['expense:single'],
            ],
        );
    }
}
