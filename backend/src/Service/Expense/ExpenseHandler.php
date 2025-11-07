<?php

declare(strict_types=1);

namespace App\Service\Expense;

use App\DTO\Expense\ExpenseDTO;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

readonly class ExpenseHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function create(User $user, ExpenseDTO $expenseDTO): Expense
    {
        $expense = (new Expense())
            ->setOwner($user)
            ->setCategory($this->getExpenseCategory($expenseDTO->getCategoryId()))
            ->setAmount($expenseDTO->getAmount())
            ->setDescription($expenseDTO->getDescription())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        return $expense;
    }

    public function update(Expense $expense, User $user, ExpenseDTO $expenseDTO): Expense
    {
        if ($expense->getOwner()->getId() !== $user->getId()) {
            throw new RuntimeException('Unauthorized to update this expense.');
        }

        $expense
            ->setCategory($this->getExpenseCategory($expenseDTO->getCategoryId()))
            ->setAmount($expenseDTO->getAmount())
            ->setDescription($expenseDTO->getDescription())
            ->setUpdatedAt(new DateTime())
        ;

        $this->entityManager->flush();

        return $expense;
    }

    public function delete(Expense $expense): void
    {
        $this->entityManager->remove($expense);
    }

    private function getExpenseCategory(int $categoryId): ExpenseCategory
    {
        $category = $this->entityManager->getRepository(ExpenseCategory::class)->find($categoryId);

        if (! $category instanceof ExpenseCategory) {
            throw new RuntimeException('Category not found.');
        }

        return $category;
    }
}
