<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Validator;

/**
 * Interface ValidatorInterface.
 */
interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value): bool;
}
