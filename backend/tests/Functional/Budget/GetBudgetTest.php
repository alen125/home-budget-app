<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Abstraction\AbstractWebTestCase;

class GetBudgetTest extends AbstractWebTestCase
{
    #[DataProvider('provideBudgetPeriods')]
    public function testGetBudgetRange(string $query, callable $expectedDatesCallback): void
    {
        $data = $this->makeRequest(Request::METHOD_GET, '/api/budget?' . $query, Response::HTTP_OK);

        $this->assertIsArray($data);
        foreach (['period', 'from', 'to', 'monthRange', 'totalSpent', 'totalBudget', 'percentageUsed'] as $key) {
            $this->assertArrayHasKey($key, $data, sprintf('Missing key "%s"', $key));
        }

        parse_str($query, $params);
        $expectedPeriod = $params['period'] ?? 'custom';
        $this->assertSame($expectedPeriod, $data['period']);

        [$expectedFrom, $expectedTo] = $expectedDatesCallback();

        $this->assertSame($expectedFrom->format('Y-m-d'), $data['from']);
        $this->assertSame($expectedTo->format('Y-m-d'), $data['to']);
    }

    public static function provideBudgetPeriods(): array
    {
        return [
            'custom period' => [
                'period=custom&from=2025-05-14&to=2025-08-20',
                fn() => [new \DateTimeImmutable('2025-05-14'), new \DateTimeImmutable('2025-08-20')],
            ],
            'current month' => [
                'period=currentMonth',
                function () {
                    $now = new \DateTimeImmutable('now');
                    return [
                        $now->modify('first day of this month')->setTime(0, 0),
                        $now->modify('last day of this month')->setTime(23, 59, 59),
                    ];
                },
            ],
            'last month' => [
                'period=lastMonth',
                function () {
                    $now = new \DateTimeImmutable('now');
                    return [
                        $now->modify('first day of last month')->setTime(0, 0),
                        $now->modify('last day of last month')->setTime(23, 59, 59),
                    ];
                },
            ],
            'current quarter' => [
                'period=currentQuarter',
                function () {
                    $now = new \DateTimeImmutable('now');
                    $month = (int)$now->format('n');
                    $quarterStartMonth = ((int)(($month - 1) / 3) * 3) + 1;
                    $start = new \DateTimeImmutable(sprintf('%04d-%02d-01', $now->format('Y'), $quarterStartMonth));
                    $end = $start->modify('+2 months')->modify('last day of this month');
                    return [$start->setTime(0, 0), $end->setTime(23, 59, 59)];
                },
            ]
        ];
    }
}