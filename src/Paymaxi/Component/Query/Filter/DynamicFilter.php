<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Validator\Adapter\CallableAdapter;

final class DynamicFilter extends AbstractFilter
{
    /** @var callable */
    private $dynamicFilter;

    /**
     * DynamicFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param callable $dynamicFilter
     */
    public function __construct(string $queryField, string $fieldName, callable $dynamicFilter)
    {
        parent::__construct($queryField, $fieldName, new CallableAdapter(function ($value) {
            return is_scalar($value);
        }));

        $this->dynamicFilter = $dynamicFilter;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $value
     *
     * @return void
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value)
    {
        if (!$this->validate($value)) {
            $this->thrower->invalidValueForKey($this->getQueryField());
        }

        call_user_func_array($this->dynamicFilter, [$queryBuilder, $value]);
    }
}
