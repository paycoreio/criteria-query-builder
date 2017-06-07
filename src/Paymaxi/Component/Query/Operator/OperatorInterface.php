<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Operator;

/**
 * Class Operator
 */
interface OperatorInterface
{
    const OP_EQ = 'eq';
    const OP_NEQ = 'neq';
    const OP_GT = 'gt';
    const OP_GTE = 'gte';
    const OP_LT = 'lt';
    const OP_LTE = 'lte';
    const OP_IN = 'in';
    const OP_NIN = 'notIn';
    const OP_CONTAINS = 'contains';

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
    public function getValidator();

    /**
     * @param $value
     *
     * @return mixed
     */
    public function normalize($value);
}
