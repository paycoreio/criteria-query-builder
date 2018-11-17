<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Sort;

/**
 * Interface SortInterface.
 */
interface SortInterface
{
    /**
     * @return string
     */
    public function getQueryField(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @param string $field
     *
     * @return bool
     */
    public function supports(string $field): bool;
}
