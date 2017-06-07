<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

interface SortInterface
{
    /**
     * @return string
     */
    public function getQueryField(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;

    public function supports(string $field);

    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $order);
}
