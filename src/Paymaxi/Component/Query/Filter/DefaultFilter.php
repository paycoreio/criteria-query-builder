<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Operator\OperatorInterface;
use Paymaxi\Component\Query\Validator\DummyValidator;

/**
 * Class DateFilter
 */
final class DefaultFilter extends AbstractFilter
{
    /** @var OperatorInterface[] */
    private $operators;

    /**
     * DateFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param array $operators
     */
    public function __construct(string $queryField, string $fieldName, array $operators = [])
    {
        parent::__construct($queryField, $fieldName, new DummyValidator());

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
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $values
     *
     * @return void
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $values)
    {
        if (!is_array($values)) {
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

            $exp = Criteria::expr();

            if (!method_exists($exp, $criteriaOperator)) {
                throw new \RuntimeException(sprintf('Criteria operator %s does not exist.', $criteriaOperator));
            }

            $criteria->andWhere($exp->{$criteriaOperator}($this->fieldName, $value));
        }
    }

    /**
     * @param OperatorInterface $operator
     * @param $value
     *
     * @return true
     */
    protected function validateWithOperator(OperatorInterface $operator, $value)
    {
        if (null !== $operator->getValidator()) {
            return call_user_func($operator->getValidator(), $value);
        }

        return parent::validate($value);
    }
}
