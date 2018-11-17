<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DynamicFilter.
 */
final class DynamicFilter extends AbstractFilter implements QueryBuilderFilterInterface
{
    /** @var callable */
    private $dynamicFilter;

    /**
     * DynamicFilter constructor.
     *
     * @param string   $queryField
     * @param callable $dynamicFilter
     */
    public function __construct(string $queryField, callable $dynamicFilter)
    {
        parent::__construct($queryField);

        $this->dynamicFilter = $dynamicFilter;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param mixed        $value
     *
     * @throws \Throwable
     */
    public function apply(QueryBuilder $queryBuilder, $value): void
    {
        if (!$this->validate($value)) {
            $this->thrower->invalidValueForKey($this->getQueryField());
        }

        \call_user_func($this->dynamicFilter, $queryBuilder, $value);
    }
}
