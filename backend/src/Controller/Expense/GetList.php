<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses', methods: [Request::METHOD_GET])]
class GetList extends AbstractController
{
    public function __invoke(
        Request $request,
        ExpenseRepository $expenseRepository,
    ): JsonResponse {
        $filters = $request->query->all();
        /** @var User $user */
        $user = $this->getUser();
        $filters['ownerId'] = $user->getId();
        return $this->json(
            data: $expenseRepository->search($filters),
            status: Response::HTTP_OK,
            context: [
                'groups' => ['expense:list'],
            ],
        );
    }
}
