<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface SortInterface
 *
 * @package Paymaxi\Component\Query\Sort
 */
interface SortInterface
{
    /**
     * @return string
     */
    public function getQueryField(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @param string $field
     *
     * @return mixed
     */
    public function supports(string $field);

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $order
     *
     * @return mixed
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $order);
}
