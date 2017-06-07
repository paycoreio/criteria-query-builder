<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ScalarValidator
 */
class ScalarFilter extends AbstractFilter
{
    /**
     * ScalarValidator constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     */
    public function __construct(string $queryField, string $fieldName)
    {
        parent::__construct($queryField, $fieldName, function ($value) {
            return is_scalar($value);
        });
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
            $this->throwValidationException(
                sprintf('Invalid value provided `%s`.', $value)
            );
        }

        $criteria->andWhere(Criteria::expr()->eq($this->fieldName, $value));
    }
}
