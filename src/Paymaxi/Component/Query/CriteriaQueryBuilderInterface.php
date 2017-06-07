<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query;

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
    public function getQb();

    /**
     * @param array $defaultOrder
     *
     * @return void
     */
    public function setDefaultOrder(array $defaultOrder);

    /**
     * @return array
     */
    public function getDefaultOrder();
}
