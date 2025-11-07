<?php

namespace App\Repository;

use App\Entity\Expense;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

class ExpenseRepository extends AbstractSearchRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function sumExpensesByUserAndPeriod(int $userId, DateTimeInterface $from, DateTimeInterface $to): float
    {
        $queryBuilder = $this->createQueryBuilder('e');
        return $this->createQueryBuilder('e')
            ->select('SUM(e.amount) as total')
            ->where('e.owner = :userId')
            ->andWhere('e.createdAt >= :from')
            ->andWhere('e.createdAt <= :to')
            ->setParameters(new ArrayCollection([
                new Parameter('userId', $userId),
                new Parameter('from', $from),
                new Parameter('to', $to),
            ]))
            ->getQuery()
            ->getSingleScalarResult() ?? 0
        ;
    }

    public function search(array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($this->searchParamExists($filters, 'ownerId')) {
            $queryBuilder->andWhere('e.owner = :ownerId')
                ->setParameter('ownerId', $filters['ownerId']);
        }

        if ($this->searchParamExists($filters, 'categoryId')) {
            $queryBuilder->andWhere('e.category = :categoryId')
                ->setParameter('categoryId', $filters['categoryId']);
        }

        if ($this->searchParamExists($filters, 'priceMin') && $filters['priceMin'] > 0) {
            $queryBuilder->andWhere('e.amount >= :priceMin')
                ->setParameter('priceMin', $filters['priceMin']);
        }

        if ($this->searchParamExists($filters, 'priceMax') && $filters['priceMax'] > 0) {
            $queryBuilder->andWhere('e.amount <= :priceMax')
                ->setParameter('priceMax', $filters['priceMax']);
        }

        return $this->buildReturn($queryBuilder, $filters);
    }

    protected function getDefaultOrderBy(): string
    {
        return 'amount';
    }

    protected function getOrderMap(): array
    {
        return [
            'amount' => 'e.amount',
        ];
    }
}
