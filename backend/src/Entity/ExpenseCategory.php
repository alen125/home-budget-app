<?php

namespace App\Entity;

use App\Entity\Abstraction\TimestampableInterface;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\ExpenseCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ExpenseCategoryRepository::class)]
class ExpenseCategory implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['expenseCategory:single', 'expenseCategory:list', 'expense:single', 'expense:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['expenseCategory:single', 'expenseCategory:list', 'expense:single', 'expense:list'])]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
