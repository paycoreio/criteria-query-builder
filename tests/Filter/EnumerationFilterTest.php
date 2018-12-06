<?php

use Neomerx\JsonApi\Exceptions\JsonApiException;
use PHPUnit\Framework\TestCase;

class EnumerationFilterTest extends TestCase
{
    /**
     * @dataProvider valid_data_provider
     * @test
     */
    public function it_validates($input, $expected)
    {
        $filter = new \Paymaxi\Component\Query\Filter\EnumerationFilter('field');

        $criteria = $this->getCriteria();

        $filter->applyCriteria($criteria, $input);

        $value = $criteria->getWhereExpression()->getValue()->getValue();
        $value = implode(',', (array)$value);

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

        $filter = new \Paymaxi\Component\Query\Filter\EnumerationFilter('field');

        $filter->applyCriteria($this->getCriteria(), $input);
    }

    public function valid_data_provider()
    {
        return [
            ['test', 'test'],
            [['a', 'b'], 'a,b'],
            [['a', 'b'], 'a,b'],
            [['a', 'b'], 'a,b'],
            [['a'], 'a'],
            [['1,2,3'], '1,2,3'],
            [[1], '1'],
            [['1,2,3', '2'], '1,2,3,2'],
        ];
    }

    public function invalid_data_provider()
    {
        return [
            [1],
            ['1,2,1'],
            [null],
            [new stdClass],
            [false],
        ];
    }
}
