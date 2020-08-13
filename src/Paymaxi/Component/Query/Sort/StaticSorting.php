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
     * @param array $sortParams
     *
     * @return void
     */
    public function applyCriteria(Criteria $criteria, array $sortParams): void
    {
        $criteria->orderBy($sortParams);
    }
}
