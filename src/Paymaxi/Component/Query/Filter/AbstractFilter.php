<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Ramsey\Uuid\Uuid;

/**
 * Class AbstractFilter
 */
abstract class AbstractFilter implements FilterInterface
{
    /** @var callable */
    protected $defaultValidator;

    /** @var string */
    protected $fieldName;

    /** @var string */
    protected $queryField;

    /**
     * AbstractFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param callable $defaultValidator
     */
    public function __construct(string $queryField, string $fieldName, callable $defaultValidator)
    {
        $this->fieldName = $fieldName;
        $this->queryField = $queryField;
        $this->defaultValidator = $defaultValidator;
    }

    /**
     * @param callable $defaultValidator
     */
    public function setDefaultValidator(callable $defaultValidator)
    {
        $this->defaultValidator = $defaultValidator;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getQueryField(): string
    {
        return $this->queryField;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function supports(string $field)
    {
        return $field === $this->getQueryField();
    }

    protected function validate($value)
    {
        return call_user_func($this->defaultValidator, $value);
    }

    abstract public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value);

    /**
     * @param $message
     */
    protected function throwValidationException(string $message)
    {
        $uuid = Uuid::getFactory()->uuid4()->toString();

        $error = new Error(
            $uuid,
            null,
            'error',
            400,
            $message
        );

        throw new JsonApiException($error);
    }
}
