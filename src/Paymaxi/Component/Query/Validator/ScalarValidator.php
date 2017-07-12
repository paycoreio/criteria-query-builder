<?php
declare(strict_types=1);

namespace Paymaxi\Component\Query\Validator;


/**
 * Class ScalarValidator
 *
 * @package Paymaxi\Component\Query\Validator
 */
final class ScalarValidator implements ValidatorInterface
{
    /**
     * @param $value
     *
     * @return bool
     */
    public function validate($value): bool
    {
        return is_scalar($value);
    }
}