<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class AbstractSearchRepository extends ServiceEntityRepository
{
    abstract protected function getDefaultOrderBy(): string;

    abstract protected function getOrderMap(): array;

    protected function buildReturn(QueryBuilder $queryBuilder, array $filters): array
    {
        $this->handleFrontOrder($queryBuilder, $filters);

        $page = $this->searchParamExists($filters, 'page') ? (int) $filters['page'] : 1;
        $limit = $this->searchParamExists($filters, 'limit') ? (int) $filters['limit'] : 10;

        $offset = ($page - 1) * $limit;


        $queryBuilder->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($queryBuilder);

        return [
            'meta' => $this->buildMeta($paginator, $page, $limit),
            'data' => iterator_to_array($paginator->getIterator()),
        ];
    }

    protected function searchParamExists(array $filters, string $paramName): bool
    {
        $paramValue = $filters[$paramName] ?? null;

        return $paramValue !== null && trim((string) $paramValue) !== '';
    }

    private function handleFrontOrder(QueryBuilder $queryBuilder, array $filters): void
    {
        $orderBy = $this->getDefaultOrderBy();
        $orderByMap = $this->getOrderMap();
        $order = 'ASC';
        $validOrders = ['ASC', 'DESC'];

        if ($this->searchParamExists($filters, 'orderBy') && array_key_exists($filters['orderBy'], $orderByMap)) {
            $orderBy = $filters['orderBy'];
        }

        if ($this->searchParamExists($filters, 'order') && in_array($filters['order'], $validOrders)) {
            $order = $filters['order'];
        }

        $queryBuilder->orderBy($orderByMap[$orderBy], $order);
    }

    private function buildMeta(Paginator $paginator, int $page, int $limit): array
    {
        $maxResults = $paginator->getQuery()->getMaxResults();
        $currentCount = count($paginator);

        return [
            'lastPage' => (int) ceil(count($paginator) / $maxResults),
            'count' => $currentCount,
            'page' => $page,
            'limit' => $limit,
        ];
    }
}
