<?php


namespace Paymaxi\Component\Query\Validator;


interface ValidatorInterface
{
    public function validate($value): bool;
}