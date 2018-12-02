<?php


use Paymaxi\Component\Query\Filter\DynamicFilter;
use PHPUnit\Framework\TestCase;

class DynamicFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_validates()
    {
        $expectedValue = 'test';
        $passedValue = null;

        
        $filter = new \Paymaxi\Component\Query\Filter\DynamicFilter('field',
            function (\Doctrine\ORM\QueryBuilder $qb, $value) use (&$passedValue) {
                $passedValue = $value;
            });


        $filter->applyQueryBuilder($this->getQbMock(), 'test');

        $this->assertSame($expectedValue, $passedValue);
    }

    private function getQbMock()
    {
        return $this->getMockBuilder(\Doctrine\ORM\QueryBuilder::class)->disableOriginalConstructor()->getMock();
    }
}
