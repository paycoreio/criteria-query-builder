<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;

/**
 * Interface CriteriaFilterInterface
 *
 * @package Paymaxi\Component\Query\Filter
 */
interface CriteriaFilterInterface extends FilterInterface
{
    /**
     * @param Criteria $criteria
     * @param mixed $value
     */
    public function applyCriteria(Criteria $criteria, $value): void;
}
