<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Validator\Adapter\CallableAdapter;

/**
 * Class BooleanFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
final class BooleanFilter extends AbstractFilter
{
    const CAST_NUMERIC_STRINGS = 1;
    const CAST_BOOLEAN_STRINGS = 2;
    const CAST_STRINGS = 4;

    /** @var int */
    private $options;

    /**
     * BooleanFilter constructor.
     *
     * @param string $queryField
     * @param string|null $fieldName
     * @param int $options
     */
    public function __construct(
        string $queryField,
        string $fieldName = null,
        int $options = self::CAST_NUMERIC_STRINGS | self::CAST_BOOLEAN_STRINGS
    ) {
        parent::__construct($queryField, $fieldName);

        $this->setValidator(new CallableAdapter(function ($value) {
            return is_bool($value);
        }));

        $this->options = $options;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $value
     *
     * @return void
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value)
    {
        if (is_string($value)) {
            $value = strtolower($value);

            if ($this->options & self::CAST_NUMERIC_STRINGS && ($value === '1' || $value === '0')) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($this->options & self::CAST_BOOLEAN_STRINGS && ($value === 'true' || $value === 'false')) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($this->options & self::CAST_STRINGS && ($value === 'yes' || $value === 'no')) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }

        if (!$this->validate($value)) {
            $this->thrower->invalidValueForKey($this->getFieldName());
        }

        $criteria->andWhere(Criteria::expr()->eq($this->fieldName, $value));
    }
}
