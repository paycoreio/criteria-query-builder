<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Operator;

use Carbon\Carbon;

/**
 * Class DateTimeOperator
 *
 * @package Paymaxi\Component\Query\Operator
 */
final class DateTimeOperator extends Operator
{
    /**
     * DateTimeOperator constructor.
     *
     * @param string $queryOperator
     * @param string $criteriaOperator
     * @param callable $validator
     */
    public function __construct(string $queryOperator, string $criteriaOperator, callable $validator = null)
    {
        $normalizer = function ($value) {
            return Carbon::createFromTimestampUTC($value);
        };

        parent::__construct($queryOperator, $criteriaOperator, $validator, $normalizer);
    }
}
