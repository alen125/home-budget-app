<?php

declare(strict_types=1);

namespace App\DTO\Budget;

use App\Enum\BudgetPeriodEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class BudgetQueryDTO
{
    public function __construct(
        #[Assert\NotBlank]
        private ?BudgetPeriodEnum $period = null,
        private ?\DateTimeInterface $from = null,
        private ?\DateTimeInterface $to = null,
    ) {
    }

    public function getPeriod(): ?BudgetPeriodEnum
    {
        return $this->period;
    }

    public function getFrom(): ?\DateTimeInterface
    {
        return $this->from;
    }

    public function getTo(): ?\DateTimeInterface
    {
        return $this->to;
    }

    #[Assert\Callback]
    public function validateConditionalFields(ExecutionContextInterface $context): void
    {
        foreach (['from', 'to'] as $field) {
            if ($this->period === BudgetPeriodEnum::CUSTOM && (empty($this->{$field}))) {
                $context
                    ->buildViolation('This field cannot be blank for custom period.')
                    ->atPath($field)
                    ->addViolation()
                ;
            }
        }
    }
}
