<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Paymaxi\Component\Query\Operator\OperatorInterface;

/**
 * Class OperatorFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
final class OperatorFilter extends AbstractFilter implements CriteriaFilterInterface
{
    /** @var ExpressionBuilder */
    private static $expressionBuilder;

    /** @var OperatorInterface[] */
    private $operators;

    /**
     * DateFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param array $operators
     */
    public function __construct(string $queryField, string $fieldName = null, array $operators = [])
    {
        parent::__construct($queryField, $fieldName);

        foreach ($operators as $operator) {
            $this->addOperator($operator);
        }
    }

    /**
     * @param OperatorInterface $operator
     *
     * @return $this
     */
    public function addOperator(OperatorInterface $operator)
    {
        $this->operators[$operator->getQueryOperator()] = $operator;

        return $this;
    }

    /**
     * @param ExpressionBuilder $expressionBuilder
     */
    public static function setExpressionBuilder(ExpressionBuilder $expressionBuilder): void
    {
        self::$expressionBuilder = $expressionBuilder;
    }

    /**
     * @return ExpressionBuilder
     */
    public function getExpressionBuilder(): ExpressionBuilder
    {
        if (null === self::$expressionBuilder) {
            self::$expressionBuilder = new ExpressionBuilder();
        }

        return self::$expressionBuilder;
    }

    /**
     * @param Criteria $criteria
     * @param array $values
     *
     * @return void
     * @throws \Throwable
     */
    public function applyCriteria(Criteria $criteria, $values): void
    {
        if (!\is_array($values)) {
            $this->thrower->invalidValueForField($this->getQueryField(), 'array');
        }

        $values = (array) $values;

        foreach ($values as $queryOperator => $value) {
            if (!array_key_exists($queryOperator, $this->operators)) {
                $this->thrower->operatorIsNotDefined($queryOperator);
            }

            $operator = $this->operators[$queryOperator];

            if (!$this->validateWithOperator($operator, $value)) {
                $this->thrower->invalidValueForOperator($operator->getQueryOperator());
            }

            $value = $operator->normalize($value);

            $criteriaOperator = $operator->getCriteriaOperator();

            $exp = $this->getExpressionBuilder();

            if (!method_exists($exp, $criteriaOperator)) {
                throw new \RuntimeException(sprintf('Criteria operator %s does not exist.', $criteriaOperator));
            }

            $criteria->andWhere($exp->{$criteriaOperator}($this->fieldName, $value));
        }
    }

    /**
     * @param OperatorInterface $operator
     * @param array|string|int|float $value
     *
     * @return bool|true
     */
    protected function validateWithOperator(OperatorInterface $operator, $value): bool
    {
        if (null !== $operator->getValidator()) {
            return \call_user_func($operator->getValidator(), $value);
        }

        return parent::validate($value);
    }
}
