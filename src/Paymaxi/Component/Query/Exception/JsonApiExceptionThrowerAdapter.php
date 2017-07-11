<?php


namespace Paymaxi\Component\Query\Exception;


use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Ramsey\Uuid\Uuid;

final class JsonApiExceptionThrowerAdapter implements QueryExceptionThrowerInterface
{

    public function invalidValueForKey(string $key)
    {
        $this->throwException(sprintf('Invalid value provided for key `%s`.', $key));
    }

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

    public function operatorIsNotDefined(string $operator)
    {
        $this->throwException(sprintf('Operator `%s` does not defined.', $operator));
    }

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