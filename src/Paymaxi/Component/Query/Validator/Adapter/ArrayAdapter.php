<?php


namespace Paymaxi\Component\Query\Validator\Adapter;


use Paymaxi\Component\Query\Validator\ValidatorInterface;

final class ArrayAdapter implements ValidatorInterface
{
    /** @var ValidatorInterface */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value): bool
    {
        if (empty($values)) {
            return false;
        }

        foreach ($values as $item) {
            if (!$this->validate($item)) {
                return false;
            }
        }

        return true;
    }
}