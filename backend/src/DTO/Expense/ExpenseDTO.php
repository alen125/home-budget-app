<?php

declare(strict_types=1);

namespace App\DTO\Expense;

use App\Entity\ExpenseCategory;
use App\Validator\EntityExists\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ExpenseDTO
{
    public function __construct(
        #[Assert\NotNull]
        #[EntityExists(entity: ExpenseCategory::class, message: 'Category not found!')]
        private ?int $categoryId = null,
        #[Assert\Positive]
        #[Assert\NotNull]
        private ?float $amount = null,
        #[Assert\Length(min: 2, max: 255)]
        #[Assert\NotBlank]
        private ?string $description = null,
    ) {
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
