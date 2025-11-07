<?php

declare(strict_types=1);

namespace App\Service\Budget;

use App\DTO\Budget\BudgetQueryDTO;
use App\Entity\User;
use App\Enum\BudgetPeriodEnum;
use App\Repository\ExpenseRepository;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;

readonly class CalculateBudget
{
    public function __construct(
        private ExpenseRepository $expenseRepository,
    ) {
    }

    public function calculate(User $user, BudgetQueryDTO $dto): array
    {
        [$start, $end] = $this->resolveDateRange($dto);
        $totalSpent = $this->expenseRepository->sumExpensesByUserAndPeriod($user->getId(), $start, $end);
        $monthRange = $this->getMonthDifferenceBetweenDates($start, $end);
        $totalBudget = $user->getBudget() * $monthRange;

        return [
            'period' => $dto->getPeriod()->value,
            'from' => $start->format('Y-m-d'),
            'to' => $end->format('Y-m-d'),
            'monthRange' => $monthRange,
            'totalSpent' => $totalSpent,
            'totalBudget' => $totalBudget,
            'percentageUsed' => $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 2) : 0,
        ];
    }

    private function resolveDateRange(BudgetQueryDTO $dto): array
    {
        if ($dto->getPeriod() === BudgetPeriodEnum::CUSTOM) {
            return [$dto->getFrom(), $dto->getTo()];
        }
        $now = new DateTimeImmutable('now');

        return match ($dto->getPeriod()) {
            BudgetPeriodEnum::CURRENT_MONTH => [
                (new DateTimeImmutable('first day of this month'))->setTime(0, 0),
                (new DateTimeImmutable('last day of this month'))->setTime(23, 59, 59),
            ],
            BudgetPeriodEnum::LAST_MONTH => [
                (new DateTimeImmutable('first day of last month'))->setTime(0, 0),
                (new DateTimeImmutable('last day of last month'))->setTime(23, 59, 59),
            ],
            BudgetPeriodEnum::CURRENT_QUARTER => $this->handleQuarterDates(),
        };
    }

    private function handleQuarterDates(): array
    {
        $now = new DateTimeImmutable();
        $month = (int) $now->format('n');
        $quarterStartMonth = ((int) (($month - 1) / 3) * 3) + 1;
        $year = (int) $now->format('Y');

        $start = new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $quarterStartMonth));
        $end = $start->modify('+2 months')->modify('last day of this month');

        return [
            $start->setTime(0, 0),
            $end->setTime(23, 59, 59),
        ];
    }

    private function getMonthDifferenceBetweenDates(DateTimeInterface $start, DateTimeInterface $end): int
    {
        $interval = new \DateInterval('P1M');
        $period = new DatePeriod($start, $interval, $end);

        return iterator_count($period);
    }
}
