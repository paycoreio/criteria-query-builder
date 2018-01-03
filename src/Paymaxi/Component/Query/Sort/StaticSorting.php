<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;

/**
 * Class StaticSorting
 *
 * @package Paymaxi\Component\Query\Sort
 */
final class StaticSorting extends AbstractSorting implements CriteriaSortInterface
{
    /**
     * @param Criteria $criteria
     * @param string $orderField
     *
     * @return void
     */
    public function apply(Criteria $criteria, string $orderField): void
    {
        $criteria->orderBy([$this->getFieldName() => $orderField]);
    }
}
