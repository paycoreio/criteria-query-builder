<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Exception;

/**
 * Interface QueryExceptionThrowerInterface.
 */
interface QueryExceptionThrowerInterface
{
    /**
     * @param string $key
     *
     * @throws \Throwable
     */
    public function invalidValueForKey(string $key);

    /**
     * @param string $operator
     *
     * @throws \Throwable
     */
    public function operatorIsNotDefined(string $operator);

    /**
     * @param string $operator
     *
     * @throws \Throwable
     */
    public function invalidValueForOperator(string $operator);

    /**
     * @param string $field
     * @param string $expectedType
     *
     * @throws \Throwable
     */
    public function invalidValueForField(string $field, string $expectedType);
}
