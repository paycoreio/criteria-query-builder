<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

use Paymaxi\Component\Query\Filter\FilterInterface;

/**
 * Interface FilterHandlerInterface.
 */
interface FilterHandlerInterface extends HandlerInterface
{
    /**
     * @param string $field
     * @param mixed  $value
     */
    public function filter(string $field, $value): void;

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter): void;
}
