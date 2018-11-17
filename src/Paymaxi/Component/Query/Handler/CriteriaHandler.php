<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

use Doctrine\Common\Collections\Criteria;
use Paymaxi\Component\Query\Filter\CriteriaFilterInterface;
use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Sort\CriteriaSortInterface;
use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Class CriteriaHandler
 *
 * @package Paymaxi\Component\Query\Handler
 */
final class CriteriaHandler extends AbstractHandler
{
    /** @var Criteria */
    private $criteria;

    /**
     * CriteriaHandler constructor.
     *
     * @param Criteria $criteria
     */
    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @param object $object
     *
     * @return bool
     */
    public function supports($object): bool
    {
        return $object instanceof CriteriaSortInterface || $object instanceof CriteriaFilterInterface;
    }

    /**
     * @param CriteriaSortInterface|SortInterface $sort
     * @param string $order
     */
    protected function handleSorting(SortInterface $sort, string $order):void
    {
        $sort->apply($this->criteria, $order);
    }

    /**
     * @param FilterInterface|CriteriaFilterInterface $filter
     * @param mixed $value
     */
    protected function handleFiltering(FilterInterface $filter, $value):void
    {
        $filter->apply($this->criteria, $value);
    }
}
