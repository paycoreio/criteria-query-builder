<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query;

use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Class CriteriaQueryBuilder
 */
interface CriteriaQueryBuilderInterface
{
    /**
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function addFilter(FilterInterface $filter);

    /**
     * @param SortInterface $sort
     *
     * @return mixed
     */
    public function addSorting(SortInterface $sort);

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQb(): QueryBuilder;

    /**
     * @param array $defaultOrder
     *
     * @return void
     */
    public function setDefaultOrder(array $defaultOrder): void;

    /**
     * @return array
     */
    public function getDefaultOrder(): array;
}
