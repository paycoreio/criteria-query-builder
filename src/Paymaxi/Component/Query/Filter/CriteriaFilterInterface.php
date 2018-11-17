<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;

/**
 * Interface CriteriaFilterInterface.
 */
interface CriteriaFilterInterface extends FilterInterface
{
    /**
     * @param Criteria $criteria
     * @param mixed    $value
     */
    public function apply(Criteria $criteria, $value): void;
}
