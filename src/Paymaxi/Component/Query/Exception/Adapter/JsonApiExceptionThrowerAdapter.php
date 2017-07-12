<?php
declare(strict_types=1);


namespace Paymaxi\Component\Query\Exception\Adapter;

use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Paymaxi\Component\Query\Exception\QueryExceptionThrowerInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class JsonApiExceptionThrowerAdapter
 *
 * @package Paymaxi\Component\Query\Exception
 */
final class JsonApiExceptionThrowerAdapter implements QueryExceptionThrowerInterface
{

    /**
     * @param string $key
     */
    public function invalidValueForKey(string $key)
    {
        $this->throwException(sprintf('Invalid value provided for key `%s`.', $key));
    }

    /**
     * @param $message
     */
    private function throwException($message)
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
    public function operatorIsNotDefined(string $operator)
    {
        $this->throwException(sprintf('Operator `%s` does not defined.', $operator));
    }

    /**
     * @param string $operator
     */
    public function invalidValueForOperator(string $operator)
    {
        $this->throwException(sprintf('Invalid value provided for operator `%s`.', $operator));
    }

    /**
     * @param string $field
     * @param string $expectedType
     *
     * @throws \Throwable
     */
    public function invalidValueForField(string $field, string $expectedType)
    {
        $this->throwException(
            sprintf('Invalid value provided for field `%s`. Expected %s type.', $field, $expectedType)
        );

    }
}