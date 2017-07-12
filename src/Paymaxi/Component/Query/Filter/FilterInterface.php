<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface FilterInterface
 */
interface FilterInterface
{
    /**
     * @param string $field
     *
     * @return bool
     */
    public function supports(string $field): bool;

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $value
     *
     * @return void
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value);

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @return string
     */
    public function getQueryField(): string;
}
