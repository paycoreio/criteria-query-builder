<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Operator;

/**
 * Class Operator
 */
class Operator implements OperatorInterface
{
    /** @var string */
    private $queryOperator;

    /** @var string */
    private $criteriaOperator;

    /** @var callable */
    private $validator;

    /** @var callable */
    private $normalizer;

    /**
     * Operator constructor.
     *
     * @param string $queryOperator
     * @param string $criteriaOperator
     * @param callable $validator
     * @param callable $normalizer
     */
    public function __construct(
        string $queryOperator,
        string $criteriaOperator,
        callable $validator = null,
        callable $normalizer = null
    ) {
        $this->queryOperator = $queryOperator;
        $this->criteriaOperator = $criteriaOperator;
        $this->validator = $validator;

        if (null !== $normalizer) {
            $this->normalizer = $normalizer;
        } else {
            $this->normalizer = function ($value) {
                return $value;
            };
        }
    }

    /**
     * @return string
     */
    public function getQueryOperator(): string
    {
        return $this->queryOperator;
    }

    /**
     * @return string
     */
    public function getCriteriaOperator(): string
    {
        return $this->criteriaOperator;
    }

    /**
     * @return callable|null
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function normalize($value)
    {
        return call_user_func($this->normalizer, $value);
    }
}
