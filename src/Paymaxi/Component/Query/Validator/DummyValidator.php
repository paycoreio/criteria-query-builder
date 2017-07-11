<?php


namespace Paymaxi\Component\Query\Validator;


final class DummyValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        return true;
    }
}