<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class EnumerationFilter extends AbstractFilter
{
    /**
     * EnumerationFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param string $enumerationClass
     */
    public function __construct(string $queryField, string $fieldName, string $enumerationClass)
    {
        parent::__construct($queryField, $fieldName, function ($value) use ($enumerationClass) {
            return call_user_func([$enumerationClass, 'validateValue'], $value);
        });
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $value
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value)
    {
        $values = explode(',', $value);

        if (!$this->validate($values)) {
            $this->throwValidationException(
                sprintf('Invalid value provided for key `%s`.', $this->getQueryField())
            );
        }

        $criteria->andWhere(Criteria::expr()->in($this->fieldName, $values));
    }

    /**
     * @param $values
     *
     * @return bool
     */
    protected function validate($values)
    {
        if (empty($values)) {
            return false;
        }

        foreach ($values as $item) {
            if (!parent::validate($item)) {
                return false;
            }
        }

        return true;
    }
}
