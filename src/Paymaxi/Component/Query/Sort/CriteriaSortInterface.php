<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;

/**
 * Interface CriteriaSortInterface
 *
 * @package Paymaxi\Component\Query\Sort
 */
interface CriteriaSortInterface extends SortInterface
{
    /**
     * @param Criteria $criteria
     * @param array $sortParams
     */
    public function applyCriteria(Criteria $criteria, array $sortParams): void;
}
