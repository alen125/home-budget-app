<?php

declare(strict_types=1);

namespace App\Enum;

enum BudgetPeriodEnum: string
{
    case CUSTOM = 'custom';
    case CURRENT_MONTH = 'currentMonth';
    case LAST_MONTH = 'lastMonth';
    case CURRENT_QUARTER = 'currentQuarter';
}
