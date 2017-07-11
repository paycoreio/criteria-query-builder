<?php
declare(strict_types=1);


namespace Paymaxi\Component\Query\Validator;


/**
 * Interface ValidatorInterface
 *
 * @package Paymaxi\Component\Query\Validator
 */
interface ValidatorInterface
{
    /**
     * @param $value
     *
     * @return bool
     */
    public function validate($value): bool;
}