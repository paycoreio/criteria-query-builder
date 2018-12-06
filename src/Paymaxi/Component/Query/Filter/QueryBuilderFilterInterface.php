<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface QueryBuilderFilterInterface
 *
 * @package Paymaxi\Component\Query\Filter
 */
interface QueryBuilderFilterInterface extends FilterInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param mixed $value
     */
    public function applyQueryBuilder(QueryBuilder $queryBuilder, $value): void;
}
