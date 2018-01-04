<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

use Paymaxi\Component\Query\Sort\SortInterface;

/**
 * Interface SortHandlerInterface
 *
 * @package Paymaxi\Component\Query\Handler
 */
interface SortHandlerInterface extends HandlerInterface
{
    /**
     * @param string $field
     * @param string $order
     */
    public function sort(string $field, string $order): void;

    /**
     * @param SortInterface $sort
     */
    public function addSorting(SortInterface $sort);
}
