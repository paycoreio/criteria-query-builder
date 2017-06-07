<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class Sorting implements SortInterface
{
    /** @var string */
    private $queryField;

    /** @var string */
    private $fieldName;

    /** @var callable */
    private $dynamicSorting;

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

    public function supports(string $field)
    {
        return $field === $this->getQueryField();
    }

    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $order)
    {
        if (null !== $this->dynamicSorting) {
            call_user_func_array($this->dynamicSorting, [$queryBuilder]);
        }

        $criteria->orderBy([$this->getFieldName() => $order]);
    }
}
