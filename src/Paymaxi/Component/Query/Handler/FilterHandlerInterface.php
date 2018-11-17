<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

use Paymaxi\Component\Query\Filter\FilterInterface;

/**
 * Interface FilterHandlerInterface
 *
 * @package Paymaxi\Component\Query\Handler
 */
interface FilterHandlerInterface extends HandlerInterface
{
    /**
     * @param string $field
     * @param mixed $value
     */
    public function filter(string $field, $value): void;

    /**
     * @param FilterInterface $filter
     *
     * @return void
     */
    public function addFilter(FilterInterface $filter):void;
}
