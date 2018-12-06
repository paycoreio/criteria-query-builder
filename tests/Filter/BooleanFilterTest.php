<?php


use Neomerx\JsonApi\Exceptions\JsonApiException;
use Paymaxi\Component\Query\Filter\BooleanFilter;
use PHPUnit\Framework\TestCase;

class BooleanFilterTest extends TestCase
{


    /**
     * @dataProvider valid_data_provider
     * @test
     */
    public function it_validates($input, $expected)
    {
        $filter = new BooleanFilter('field', 'field', BooleanFilter::CAST_ALL);

        $criteria = $this->getCriteria();

        $filter->applyCriteria($criteria, $input);

        $value = $criteria->getWhereExpression()->getValue()->getValue();

        $this->assertSame($expected, $value);
    }

    private function getCriteria()
    {
        return new \Doctrine\Common\Collections\Criteria();
    }

    /**
     * @dataProvider invalid_data_provider
     * @test
     */
    public function it_does_not_validate($input)
    {
        $this->expectException(JsonApiException::class);

        $filter = new \Paymaxi\Component\Query\Filter\BooleanFilter('field');

        $filter->applyCriteria($this->getCriteria(), $input);
    }

    public function valid_data_provider()
    {
        return [
            ['true', true],
            ['yes', true],
            ['no', false],
            ['false', false],
            ['1', true],
            ['0', false],
        ];
    }

    public function invalid_data_provider()
    {
        return [
            [1],
            ['yes'],
            ['no'],
            ['1,2,1'],
            [null],
            [new stdClass],
        ];
    }
}
