<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface QueryBuilderSortInterface
 *
 * @package Paymaxi\Component\Query\Sort
 */
interface QueryBuilderSortInterface extends SortInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string $orderField
     */
    public function applyQueryBuilder(QueryBuilder $queryBuilder, string $orderField): void;
}
