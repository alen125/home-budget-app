<?php

namespace App\Repository;

use App\Entity\ExpenseCategory;
use Doctrine\Persistence\ManagerRegistry;

class ExpenseCategoryRepository extends AbstractSearchRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseCategory::class);
    }

    public function search(array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('ec');

        return $this->buildReturn($queryBuilder, $filters);
    }

    protected function getDefaultOrderBy(): string
    {
        return 'name';
    }

    protected function getOrderMap(): array
    {
        return [
            'name' => 'ec.name',
        ];
    }
}
