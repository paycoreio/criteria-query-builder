<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ScalarFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
final class ScalarFilter extends AbstractFilter
{
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
