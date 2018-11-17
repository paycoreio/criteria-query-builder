<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

/**
 * Interface FilterInterface.
 */
interface FilterInterface
{
    /**
     * @param string $field
     *
     * @return bool
     */
    public function supports(string $field): bool;

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @return string
     */
    public function getQueryField(): string;
}
