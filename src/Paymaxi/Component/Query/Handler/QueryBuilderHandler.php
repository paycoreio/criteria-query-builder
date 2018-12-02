<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Filter\QueryBuilderFilterInterface;
use Paymaxi\Component\Query\Sort\QueryBuilderSortInterface;
use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Class QueryBuilderHandler
 *
 * @package Paymaxi\Component\Query\Handler
 */
final class QueryBuilderHandler extends AbstractHandler
{
    /** @var QueryBuilder */
    private $queryBuilder;
    
    /**
     * QueryBuilderHandler constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param object $object
     *
     * @return bool
     */
    public function supports($object):bool
    {
        return $object instanceof QueryBuilderSortInterface || $object instanceof QueryBuilderFilterInterface;
    }

    /**
     * @param QueryBuilderSortInterface|SortInterface $sort
     * @param string $order
     */
    protected function handleSorting(SortInterface $sort, string $order)  :void
    {
        $sort->applyQueryBuilder($this->queryBuilder, $order);
    }

    /**
     * @param FilterInterface|QueryBuilderFilterInterface $filter
     * @param mixed $value
     */
    protected function handleFiltering(FilterInterface $filter, $value)    :void
    {
        $filter->applyQueryBuilder($this->queryBuilder, $value);
    }
}
