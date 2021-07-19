<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Paymaxi\Component\Query\Validator\Adapter\ArrayAdapter;
use Paymaxi\Component\Query\Validator\ValidatorInterface;

/**
 * Class EnumerationFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
final class EnumerationFilter extends AbstractFilter implements CriteriaFilterInterface
{
    /** @var string */
    private $delimiter;

    /**
     * EnumerationFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param string $delimiter
     */
    public function __construct(string $queryField, string $fieldName = null, string $delimiter = ',')
    {
        parent::__construct($queryField, $fieldName);
        $this->delimiter = $delimiter;
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        parent::setValidator(new ArrayAdapter($validator));
    }


    /**
     * @param Criteria $criteria
     * @param mixed $value
     *
     * @throws \Throwable
     */
    public function applyCriteria(Criteria $criteria, $value): void
    {
        $values = $value;

        if (!\is_string($value) && !\is_array($value)) {
            $this->thrower->invalidValueForKey($this->getQueryField());
        }

        if (\is_string($value)) {
            $values = explode($this->delimiter, $value);
        }

        if (!$this->validate($values)) {
            $this->thrower->invalidValueForKey($this->getQueryField());
        }

        $inValues = [];
        $notInValues = [];

        foreach ($values as $item) {
            if (strpos((string) $item, self::REVERSE_FILTER_SYMBOL) === 0) {
                $notInValues[] =  substr((string) $item, 1);
                continue;
            }
            $inValues[] = $item;
        }

        if (count($notInValues) > 0) {
            $criteria->andWhere(Criteria::expr()->notIn($this->fieldName, $notInValues));
        } else {
            $criteria->andWhere(Criteria::expr()->in($this->fieldName, $inValues));
        }
    }
}
