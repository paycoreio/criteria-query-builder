<?php

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
}
