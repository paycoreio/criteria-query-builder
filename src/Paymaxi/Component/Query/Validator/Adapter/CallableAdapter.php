<?php


namespace Paymaxi\Component\Query\Validator\Adapter;


use Paymaxi\Component\Query\Validator\ValidatorInterface;

final class CallableAdapter implements ValidatorInterface
{
    /*** @var callable */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function validate($value): bool
    {
        return call_user_func($this->callable, $value);
    }
}