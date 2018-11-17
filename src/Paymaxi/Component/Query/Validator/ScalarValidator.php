<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Validator;

/**
 * Class ScalarValidator.
 */
final class ScalarValidator implements ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value): bool
    {
        return \is_scalar($value);
    }
}
