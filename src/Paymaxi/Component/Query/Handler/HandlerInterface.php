<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Handler;

/**
 * Interface HandlerInterface.
 */
interface HandlerInterface
{
    /**
     * @param object $object
     *
     * @return bool
     */
    public function supports($object): bool;
}
