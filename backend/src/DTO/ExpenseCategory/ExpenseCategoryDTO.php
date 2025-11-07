<?php

declare(strict_types=1);

namespace App\DTO\ExpenseCategory;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ExpenseCategoryDTO
{
    public function __construct(
        #[Assert\Length(min: 2, max: 255)]
        #[Assert\NotBlank]
        private ?string $name = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
