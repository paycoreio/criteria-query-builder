<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Handler\AbstractHandler;
use Paymaxi\Component\Query\Handler\CriteriaHandler;
use Paymaxi\Component\Query\Handler\HandlerInterface;
use Paymaxi\Component\Query\Handler\QueryBuilderHandler;
use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Class CriteriaQueryBuilder
 *
 */
class CriteriaQueryBuilder implements CriteriaQueryBuilderInterface
{
    /** @var Criteria */
    private $criteria;

    /** @var array */
    private $filterParams;

    /** @var array */
    private $sortingFields;

    /** @var array */
    private $defaultOrder;

    /** @var \Doctrine\ORM\QueryBuilder */
    private $qb;

    /** @var HandlerInterface[]|AbstractHandler[] */
    private $handlers;

    /** @var bool */
    private $applied = false;

    /**
     * @param EntityRepository $repository
     * @param array $filterParams
     * @param array $sortingFields
     *
     * @internal param ApiManagerInterface $manager
     */
    public function __construct(EntityRepository $repository, array $filterParams = [], array $sortingFields = [])
    {
        $this->qb = $repository->createQueryBuilder('e');
        $this->criteria = new Criteria();

        $this->initDefaultHandlers();
        $this->setFilterParams($filterParams);
        $this->setSortingFields($sortingFields);
    }

    protected function initDefaultHandlers(): void
    {
        $this->handlers[] = new CriteriaHandler($this->criteria);
        $this->handlers[] = new QueryBuilderHandler($this->qb);
    }

    /**
     * @param FilterInterface $filter
     *
     * @return CriteriaQueryBuilderInterface
     */
    public function addFilter(FilterInterface $filter): CriteriaQueryBuilderInterface
    {
        $supports = false;

        foreach ($this->handlers as $handler) {
            if ($handler->supports($filter)) {
                $supports = true;
                $handler->addFilter($filter);
            }
        }

        if (!$supports) {
            throw new \RuntimeException('No available handler for this filter.');
        }

        $this->applied = false;

        return $this;
    }

    /**
     * @param SortInterface $sort
     *
     * @return CriteriaQueryBuilderInterface
     */
    public function addSorting(SortInterface $sort): CriteriaQueryBuilderInterface
    {
        $supports = false;

        foreach ($this->handlers as $handler) {
            if ($handler->supports($sort)) {
                $supports = true;
                $handler->addSorting($sort);
            }
        }

        if (!$supports) {
            throw new \RuntimeException('No available handler for this sorting.');
        }

        $this->applied = false;

        return $this;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getQb(): \Doctrine\ORM\QueryBuilder
    {
        $clone = clone $this;
        $clone->apply();

        return $clone->qb->addCriteria($clone->getCriteria());
    }

    /**
     * It caused changes in qb and criteria
     */
    private function apply(): void
    {
        if ($this->applied) {
            return;
        }

        if (0 === \count($this->sortingFields)) {
            $this->criteria->orderBy($this->getDefaultOrder());
        }

        $this->applySorting();
        $this->applyFilters();

        $this->applied = true;
    }

    /**
     * @return Criteria
     */
    public function getCriteria(): Criteria
    {
        $this->apply();

        return $this->criteria;
    }

    private function applyFilters()
    {
        foreach ($this->filterParams as $field => $value) {
            foreach ($this->handlers as $handler) {
                $handler->filter($field, $value);
            }
        }
    }

    private function applySorting()
    {
        foreach ($this->sortingFields as $field => $order) {
            foreach ($this->handlers as $handler) {
                $handler->sort($field, $order);
            }
        }
    }

    /**
     * @return array
     */
    public function getDefaultOrder(): array
    {
        return !empty($this->defaultOrder) ? $this->defaultOrder : ['created' => 'DESC'];
    }

    /**
     * @param array $defaultOrder
     */
    public function setDefaultOrder(array $defaultOrder): void
    {
        $this->defaultOrder = $defaultOrder;
    }

    /**
     * @param array $filterParams
     */
    public function setFilterParams(array $filterParams)
    {
        $this->applied = false;

        $this->filterParams = $filterParams;
    }

    /**
     * @param array $sortingFields
     */
    public function setSortingFields(array $sortingFields)
    {
        $this->applied = false;
        
        $this->sortingFields = $sortingFields;
    }
}
