<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;

/**
 * Class StaticSorting
 * TODO: add final keyword after 0.4.0 release.
 */
/*final*/ class StaticSorting extends AbstractSorting implements CriteriaSortInterface
{
    /**
     * @param Criteria $criteria
     * @param string   $orderField
     */
    public function apply(Criteria $criteria, string $orderField): void
    {
        $criteria->orderBy([$this->getFieldName() => $orderField]);
    }
}
