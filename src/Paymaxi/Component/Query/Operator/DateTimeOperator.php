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
     * @param string $format
     */
    public function __construct(string $queryOperator, string $criteriaOperator, string $format = 'U')
    {
        $normalizer = function ($value) use ($format) {
            return Carbon::createFromFormat($format, $value);
        };

        $validator = function ($value) use ($format) {
            try {
                Carbon::createFromFormat($format, $value);
            } catch (\Throwable $exception) {
                return false;
            }

            return true;
        };

        parent::__construct($queryOperator, $criteriaOperator, $validator, $normalizer);
    }
}
