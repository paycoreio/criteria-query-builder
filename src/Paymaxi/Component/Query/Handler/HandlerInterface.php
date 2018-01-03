<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

/**
 * Interface HandlerInterface
 *
 * @package Paymaxi\Component\Query\Handler
 */
interface HandlerInterface
{
    /**
     * @param object $object
     *
     * @return bool
     */
    public function supports($object): bool;

    /**
     * @param string $field
     * @param string $order
     */
    public function sort(string $field, string $order): void;

    /**
     * @param string $field
     * @param mixed $value
     */
    public function filter(string $field, $value): void;
}
