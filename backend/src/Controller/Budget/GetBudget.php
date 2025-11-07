<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\DTO\Budget\BudgetQueryDTO;
use App\Entity\User;
use App\Service\Budget\CalculateBudget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/budget')]
class GetBudget extends AbstractController
{
    public function __invoke(
        #[MapQueryString]
        BudgetQueryDTO $budgetQueryDTO,
        CalculateBudget $calculateBudget,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json(
            data: $calculateBudget->calculate($user, $budgetQueryDTO),
            status: Response::HTTP_OK,
            context: [
                'groups' => ['expense:list'],
            ],
        );
    }
}
