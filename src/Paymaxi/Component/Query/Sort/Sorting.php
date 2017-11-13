<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Class Sorting
 *
 * @package Paymaxi\Component\Query\Sort
 */
class Sorting implements SortInterface
{
    /** @var string */
    private $queryField;

    /** @var string */
    private $fieldName;

    /** @var callable */
    private $dynamicSorting;

    /**
     * Sorting constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param callable|null $dynamicSorting
     */
    public function __construct(string $queryField, string $fieldName, callable $dynamicSorting = null)
    {
        $this->queryField = $queryField;
        $this->fieldName = $fieldName;
        $this->dynamicSorting = $dynamicSorting;
    }

    /**
     * @return string
     */
    public function getQueryField(): string
    {
        return $this->queryField;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function supports(string $field): bool
    {
        return $field === $this->getQueryField();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $order
     *
     * @return mixed|void
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $order): void
    {
        if (null !== $this->dynamicSorting) {
            call_user_func($this->dynamicSorting, $queryBuilder);
        }

        $criteria->orderBy([$this->getFieldName() => $order]);
    }
}
