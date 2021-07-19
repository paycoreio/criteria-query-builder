<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;

/**
 * Class ScalarFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
final class ScalarFilter extends AbstractFilter implements CriteriaFilterInterface
{
    /**
     * @param Criteria $criteria
     * @param mixed $value
     *
     * @return void
     * @throws \Throwable
     */
    public function applyCriteria(Criteria $criteria, $value): void
    {
        if (!$this->validate($value)) {
            $this->thrower->invalidValueForKey($this->getFieldName());
        }

        if (strpos($value, self::REVERSE_FILTER_SYMBOL) === 0) {
            $criteria->andWhere(Criteria::expr()->neq($this->fieldName, substr($value, 1)));
        } else {
            $criteria->andWhere(Criteria::expr()->eq($this->fieldName, $value));
        }
    }
}
