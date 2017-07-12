<?php
declare(strict_types=1);


namespace Paymaxi\Component\Query\Validator\Adapter;

use Paymaxi\Component\Query\Validator\ValidatorInterface;

/**
 * Class ArrayAdapter
 *
 * @package Paymaxi\Component\Query\Validator\Adapter
 */
final class ArrayAdapter implements ValidatorInterface
{
    /** @var ValidatorInterface */
    private $validator;

    /**
     * ArrayAdapter constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function validate($value): bool
    {
        if (!is_array($value) || 0 === count($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->validator->validate($item)) {
                return false;
            }
        }

        return true;
    }
}