<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query;

use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Class CriteriaQueryBuilder.
 */
interface CriteriaQueryBuilderInterface
{
    /**
     * @param FilterInterface $filter
     *
     * @return CriteriaQueryBuilderInterface
     */
    public function addFilter(FilterInterface $filter): self;

    /**
     * @param SortInterface $sort
     *
     * @return CriteriaQueryBuilderInterface
     */
    public function addSorting(SortInterface $sort): self;

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQb(): QueryBuilder;

    /**
     * @param array $defaultOrder
     */
    public function setDefaultOrder(array $defaultOrder): void;

    /**
     * @return array
     */
    public function getDefaultOrder(): array;
}
