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

    /** @var bool */
    private $strictUniqueControl = true;

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
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value): bool
    {
        if (!\is_array($value) || 0 === \count($value)) {
            return false;
        }

        if ($this->isStrictUniqueControl() && \count($value) > \count(\array_unique($value))) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->validator->validate($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isStrictUniqueControl(): bool
    {
        return $this->strictUniqueControl;
    }

    /**
     * @param bool $strictUniqueControl
     */
    public function setStrictUniqueControl(bool $strictUniqueControl): void
    {
        $this->strictUniqueControl = $strictUniqueControl;
    }
}
