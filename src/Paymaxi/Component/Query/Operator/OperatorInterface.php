<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Operator;

/**
 * Class Operator.
 */
interface OperatorInterface
{
    public const OP_EQ = 'eq';
    public const OP_NEQ = 'neq';
    public const OP_GT = 'gt';
    public const OP_GTE = 'gte';
    public const OP_LT = 'lt';
    public const OP_LTE = 'lte';
    public const OP_IN = 'in';
    public const OP_NIN = 'notIn';
    public const OP_CONTAINS = 'contains';

    /**
     * @return string
     */
    public function getQueryOperator(): string;

    /**
     * @return string
     */
    public function getCriteriaOperator(): string;

    /**
     * @return callable|null
     */
    public function getValidator(): ?callable;

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function normalize($value);
}
