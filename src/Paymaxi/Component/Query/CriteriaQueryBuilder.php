<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Handler\AbstractHandler;
use Paymaxi\Component\Query\Handler\CriteriaHandler;
use Paymaxi\Component\Query\Handler\FilterHandlerInterface;
use Paymaxi\Component\Query\Handler\HandlerInterface;
use Paymaxi\Component\Query\Handler\QueryBuilderHandler;
use Paymaxi\Component\Query\Handler\SortHandlerInterface;
use Paymaxi\Component\Query\Sort\SortInterface;
use Sylius\Component\Registry\ServiceRegistry;

/**
 * Class CriteriaQueryBuilder
 *
 */
class CriteriaQueryBuilder implements CriteriaQueryBuilderInterface
{
    /** @var Criteria */
    protected $criteria;

    /** @var \Doctrine\ORM\QueryBuilder */
    protected $qb;

    /** @var array */
    private $filterParams;

    /** @var array */
    private $sortingFields;

    /** @var array */
    private $defaultOrder;

    /** @var ServiceRegistry|HandlerInterface[]|AbstractHandler[] */
    private $handlers;

    /** @var bool */
    private $applied = false;

    public const DEFAULT_ORDER_FIELD = 'created';

    private $supportedSortingFields = [];

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
        $this->handlers = new ServiceRegistry(HandlerInterface::class);

        $this->initDefaultHandlers();
        $this->setFilterParams($filterParams);
        $this->setSortingFields($sortingFields);
    }

    protected function initDefaultHandlers(): void
    {
        $this->handlers->register(CriteriaHandler::class, new CriteriaHandler($this->criteria));
        $this->handlers->register(QueryBuilderHandler::class, new QueryBuilderHandler($this->qb));
    }

    /**
     * @param FilterInterface $filter
     *
     * @return CriteriaQueryBuilderInterface
     */
    public function addFilter(FilterInterface $filter): CriteriaQueryBuilderInterface
    {
        $supports = false;

        /** @var AbstractHandler $handler */
        foreach ($this->handlers->all() as $handler) {
            if ($handler instanceof FilterHandlerInterface && $handler->supports($filter)) {
                $supports = true;
                $handler->addFilter($filter);
            }
        }

        if (!$supports) {
            throw new \RuntimeException('No available handler for this filter.');
        }

        $this->resetApply();

        return $this;
    }

    protected function resetApply():void
    {
        $this->applied = false;
    }

    /**
     * @param SortInterface $sort
     *
     * @return CriteriaQueryBuilderInterface
     */
    public function addSorting(SortInterface $sort): CriteriaQueryBuilderInterface
    {
        $supports = false;

        /** @var AbstractHandler $handler */
        foreach ($this->handlers->all() as $handler) {
            if ($handler instanceof SortHandlerInterface && $handler->supports($sort)) {
                $supports = true;
                $handler->addSorting($sort);
                $this->supportedSortingFields[] = $sort->getFieldName();
            }
        }

        if (!$supports) {
            throw new \RuntimeException('No available handler for this sorting.');
        }

        $this->resetApply();

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
    protected function apply(): void
    {
        if ($this->applied) {
            return;
        }

        if (0 === \count($this->sortingFields)) {
            $this->criteria->orderBy($this->getDefaultOrder());
        } else {
            if (!isset($this->sortingFields[self::DEFAULT_ORDER_FIELD])) {
                $this->sortingFields = array_merge($this->sortingFields, $this->orderByDefaultField());
            }
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
            foreach ($this->handlers->all() as $handler) {
                if ($handler instanceof FilterHandlerInterface) {
                    $handler->filter($field, $value);
                }
            }
        }
    }

    private function applySorting()
    {
        foreach ($this->sortingFields as $field => $order) {
            foreach ($this->handlers->all() as $handler) {
                if ($handler instanceof SortHandlerInterface) {
                    $handler->sort($field, $order);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getDefaultOrder(): array
    {
        $order = $this->defaultOrder ?? [];
        if (in_array(self::DEFAULT_ORDER_FIELD, $this->supportedSortingFields) &&
            !isset($order[self::DEFAULT_ORDER_FIELD])) {
            $order = array_merge($order, $this->orderByDefaultField());
        }
        return $order;
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
        $this->resetApply();

        $this->filterParams = $filterParams;
    }

    /**
     * @param array $sortingFields
     */
    public function setSortingFields(array $sortingFields)
    {
        $this->resetApply();
        
        $this->sortingFields = $sortingFields;
    }

    /**
     * @return ServiceRegistry
     */
    public function getHandlers():ServiceRegistry
    {
        return $this->handlers;
    }

    /**
     * @param string $identifier
     * @param HandlerInterface $handler
     */
    public function addHandler(string $identifier, HandlerInterface $handler):void
    {
        $this->handlers->register($identifier, $handler);
    }

    public function getSupportedSortingFields() : array
    {
        return $this->supportedSortingFields;
    }

    private function orderByDefaultField() : array
    {
        return [self::DEFAULT_ORDER_FIELD => 'DESC'];
    }
}
