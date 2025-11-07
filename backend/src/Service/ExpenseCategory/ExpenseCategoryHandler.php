<?php

declare(strict_types=1);

namespace App\Service\ExpenseCategory;

use App\DTO\ExpenseCategory\ExpenseCategoryDTO;
use App\Entity\ExpenseCategory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

readonly class ExpenseCategoryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(ExpenseCategoryDTO $expenseCategoryDTO): ExpenseCategory
    {
        $expenseCategory = (new ExpenseCategory())
            ->setName($expenseCategoryDTO->getName())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;

        $this->entityManager->persist($expenseCategory);
        $this->entityManager->flush();

        return $expenseCategory;
    }

    public function update(ExpenseCategory $expenseCategory, ExpenseCategoryDTO $expenseCategoryDTO): ExpenseCategory
    {
        $expenseCategory
            ->setName($expenseCategoryDTO->getName())
            ->setUpdatedAt(new DateTime())
        ;
        $this->entityManager->remove($expenseCategory);

        return $expenseCategory;
    }

    public function delete(ExpenseCategory $expenseCategory): void
    {
        $this->entityManager->remove($expenseCategory);
    }
}
