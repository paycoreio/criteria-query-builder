<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Validator\Adapter\CallableAdapter;

/**
 * Class ScalarValidator
 */
final class ScalarFilter extends AbstractFilter
{
    /**
     * ScalarValidator constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     */
    public function __construct(string $queryField, string $fieldName)
    {
        parent::__construct($queryField, $fieldName, new CallableAdapter(function ($value) {
            return is_scalar($value);
        }));
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
            $this->thrower->invalidValueForKey($this->getFieldName());
        }

        $criteria->andWhere(Criteria::expr()->eq($this->fieldName, $value));
    }
}
