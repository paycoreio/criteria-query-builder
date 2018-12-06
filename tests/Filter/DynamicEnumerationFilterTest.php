<?php

use Neomerx\JsonApi\Exceptions\JsonApiException;
use PHPUnit\Framework\TestCase;

class DynamicEnumerationFilterTest extends TestCase
{
    /**
     * @dataProvider valid_data_provider
     * @test
     */
    public function it_validates($input, $expected)
    {
        $passedValue = null;

        $filter = new \Paymaxi\Component\Query\Filter\DynamicEnumerationFilter('field',
            function (\Doctrine\ORM\QueryBuilder $qb, $value) use (&$passedValue) {
                $passedValue = implode(',', $value);
            });


        $filter->applyQueryBuilder($this->getQbMock(), $input);

        $this->assertSame($expected, $passedValue);
    }

    /**
     * @dataProvider invalid_data_provider
     * @test
     */
    public function it_does_not_validate($input)
    {
        $this->expectException(JsonApiException::class);

        $filter = new \Paymaxi\Component\Query\Filter\DynamicEnumerationFilter('field',
            function (\Doctrine\ORM\QueryBuilder $qb, $value) {
            }
        );

        $filter->applyQueryBuilder($this->getQbMock(), $input);
    }

    private function getQbMock()
    {
        return $this->getMockBuilder(\Doctrine\ORM\QueryBuilder::class)->disableOriginalConstructor()->getMock();
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
            [['1,2,3','2'], '1,2,3,2'],
        ];
    }

    public function invalid_data_provider()
    {
        return [
            [1],
            [['a', 'a', 2]],
            [null],
            [new stdClass],
            [false],
        ];
    }
}
