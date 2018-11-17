<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

use Paymaxi\Component\Query\Filter\FilterInterface;
use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Class AbstractHandler.
 */
abstract class AbstractHandler implements SortHandlerInterface, FilterHandlerInterface
{
    /** @var SortInterface[] */
    protected $sortingFields = [];

    /** @var FilterInterface[] */
    protected $filteringFields = [];

    /**
     * @param SortInterface $sort
     */
    public function addSorting(SortInterface $sort): void
    {
        $this->sortingFields[] = $sort;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter): void
    {
        $this->filteringFields[] = $filter;
    }

    /**
     * @param SortInterface $sort
     * @param string        $order
     */
    abstract protected function handleSorting(SortInterface $sort, string $order): void;

    /**
     * @param FilterInterface $filter
     * @param mixed           $value
     */
    abstract protected function handleFiltering(FilterInterface $filter, $value): void;

    /**
     * @param string $field
     * @param string $order
     */
    public function sort(string $field, string $order): void
    {
        /** @var SortInterface $sort */
        foreach ($this->sortingFields as $sort) {
            if ($sort->supports($field)) {
                $this->handleSorting($sort, $order);
            }
        }
    }

    /**
     * @param string $field
     * @param mixed  $value
     */
    public function filter(string $field, $value): void
    {
        /** @var FilterInterface $filteringField */
        foreach ($this->filteringFields as $filteringField) {
            if ($filteringField->supports($field)) {
                $this->handleFiltering($filteringField, $value);
            }
        }
    }
}
