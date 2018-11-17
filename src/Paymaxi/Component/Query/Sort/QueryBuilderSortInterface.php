<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface QueryBuilderSortInterface.
 */
interface QueryBuilderSortInterface extends SortInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $orderField
     */
    public function apply(QueryBuilder $queryBuilder, string $orderField): void;
}
