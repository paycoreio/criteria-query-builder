<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Exception\Adapter\JsonApiExceptionThrowerAdapter;
use Paymaxi\Component\Query\Exception\QueryExceptionThrowerInterface;
use Paymaxi\Component\Query\Validator\ScalarValidator;
use Paymaxi\Component\Query\Validator\ValidatorInterface;


/**
 * Class AbstractFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
abstract class AbstractFilter implements FilterInterface
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var string */
    protected $fieldName;

    /** @var string */
    protected $queryField;

    /** @var QueryExceptionThrowerInterface */
    protected $thrower;

    /**
     * AbstractFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param ValidatorInterface $validator
     */
    public function __construct(string $queryField, string $fieldName = null, ValidatorInterface $validator = null)
    {
        if (null === $fieldName) {
            $fieldName = $queryField;
        }
        
        $this->fieldName = $fieldName;
        $this->queryField = $queryField;

        if (null === $validator) {
            $validator = new ScalarValidator();
        }
        
        $this->validator = $validator;
        $this->thrower = new JsonApiExceptionThrowerAdapter();
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function supports(string $field): bool
    {
        return $field === $this->getQueryField();
    }

    /**
     * @return string
     */
    public function getQueryField(): string
    {
        return $this->queryField;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $value
     *
     * @return void
     */
    abstract public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value);

    /**
     * @param QueryExceptionThrowerInterface $thrower
     */
    public function setThrower(QueryExceptionThrowerInterface $thrower)
    {
        $this->thrower = $thrower;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected function validate($value): bool
    {
        return $this->validator->validate($value);
    }
}
