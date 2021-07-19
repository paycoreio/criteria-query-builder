<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Paymaxi\Component\Query\Exception\Adapter\JsonApiExceptionThrower;
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

    protected const REVERSE_FILTER_SYMBOL = '-';

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
        
        $this->setValidator($validator);
        $this->setThrower(new JsonApiExceptionThrower());
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
     * @param QueryExceptionThrowerInterface $thrower
     */
    public function setThrower(QueryExceptionThrowerInterface $thrower): void
    {
        $this->thrower = $thrower;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function validate($value): bool
    {
        return $this->validator->validate($value);
    }
}
