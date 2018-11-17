<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

/**
 * Class Sorting
 *
 * @package Paymaxi\Component\Query\Sort
 */
abstract class AbstractSorting implements SortInterface
{
    /** @var string */
    private $queryField;

    /** @var string */
    private $fieldName;

    /**
     * Sorting constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     */
    public function __construct(string $queryField, string $fieldName = null)
    {
        $this->queryField = $queryField;

        if (null === $fieldName) {
            $fieldName = $queryField;
        }

        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getQueryField(): string
    {
        return $this->queryField;
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
}
