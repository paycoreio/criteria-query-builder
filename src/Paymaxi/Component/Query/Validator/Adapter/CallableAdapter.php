<?php
declare(strict_types=1);


namespace Paymaxi\Component\Query\Validator\Adapter;

use Paymaxi\Component\Query\Validator\ValidatorInterface;

/**
 * Class CallableAdapter
 *
 * @package Paymaxi\Component\Query\Validator\Adapter
 */
final class CallableAdapter implements ValidatorInterface
{
    /*** @var callable */
    private $callable;

    /**
     * CallableAdapter constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value): bool
    {
        return \call_user_func($this->callable, $value);
    }
}
