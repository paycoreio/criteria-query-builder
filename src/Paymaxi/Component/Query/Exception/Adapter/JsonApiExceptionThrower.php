<?php
declare(strict_types=1);


namespace Paymaxi\Component\Query\Exception\Adapter;

use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Paymaxi\Component\Query\Exception\QueryExceptionThrowerInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class JsonApiExceptionThrower
 *
 * @package Paymaxi\Component\Query\Exception
 */
final class JsonApiExceptionThrower implements QueryExceptionThrowerInterface
{

    /**
     * @param string $key
     */
    public function invalidValueForKey(string $key): void
    {
        $this->throwException(sprintf('Invalid value provided for key `%s`.', $key));
    }

    /**
     * @param string $message
     */
    private function throwException(string $message)
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

    /**
     * @param string $operator
     */
    public function operatorIsNotDefined(string $operator): void
    {
        $this->throwException(sprintf('Operator `%s` does not defined.', $operator));
    }

    /**
     * @param string $operator
     */
    public function invalidValueForOperator(string $operator): void
    {
        $this->throwException(sprintf('Invalid value provided for operator `%s`.', $operator));
    }

    /**
     * @param string $field
     * @param string $expectedType
     *
     * @throws \Throwable
     */
    public function invalidValueForField(string $field, string $expectedType): void
    {
        $this->throwException(
            sprintf('Invalid value provided for field `%s`. Expected %s type.', $field, $expectedType)
        );
    }
}
