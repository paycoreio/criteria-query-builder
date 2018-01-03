<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DynamicSorting
 *
 * @package Paymaxi\Component\Query\Sort
 */
final class DynamicSorting extends Sorting implements QueryBuilderSortInterface
{
    /** @var callable */
    private $dynamicSorting;

    /**
     * DynamicSorting constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param callable $soring
     */
    public function __construct(string $queryField, string $fieldName, callable $soring)
    {
        parent::__construct($queryField, $fieldName);
        $this->dynamicSorting = $soring;
    }


    /**
     * @param QueryBuilder $queryBuilder
     * @param string $orderField
     *
     * @return void
     */
    public function apply(QueryBuilder $queryBuilder, string $orderField): void
    {
        \call_user_func($this->dynamicSorting, $queryBuilder);
    }
}
