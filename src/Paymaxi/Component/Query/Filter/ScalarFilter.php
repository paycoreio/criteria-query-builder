<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;

/**
 * Class ScalarFilter.
 */
final class ScalarFilter extends AbstractFilter implements CriteriaFilterInterface
{
    /**
     * @param Criteria $criteria
     * @param mixed    $value
     *
     * @throws \Throwable
     */
    public function apply(Criteria $criteria, $value): void
    {
        if (!$this->validate($value)) {
            $this->thrower->invalidValueForKey($this->getFieldName());
        }

        $criteria->andWhere(Criteria::expr()->eq($this->fieldName, $value));
    }
}
