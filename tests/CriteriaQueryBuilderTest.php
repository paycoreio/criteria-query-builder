<?php

namespace Paymaxi\Component\Query\Tests;

use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Paymaxi\Component\Query\CriteriaQueryBuilder;
use Paymaxi\Component\Query\Filter\BooleanFilter;
use Paymaxi\Component\Query\Filter\OperatorFilter;
use Paymaxi\Component\Query\Filter\ScalarFilter;
use Paymaxi\Component\Query\Operator\DateTimeOperator;
use Paymaxi\Component\Query\Operator\OperatorInterface;
use Paymaxi\Component\Query\Tests\Entity\Author;
use Paymaxi\Component\Query\Tests\Entity\Book;
use PHPUnit\Framework\TestCase;

class CriteriaQueryBuilderTest extends TestCase
{
    /** @var SchemaTool */
    protected $schemaTool;

    /** @var EntityManager */
    private $em;

    public function setUp()
    {
        $connectionParams = ['url' => 'sqlite://:memory:'];
        $config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/mapping"), true);
        $connection = DriverManager::getConnection($connectionParams, $config);
        $this->em = EntityManager::create($connection, $config);

        $this->schemaTool = new SchemaTool($this->em);
        $this->schemaTool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    public function tearDown()
    {
        $this->schemaTool->dropDatabase();
    }

    public function getAuthorQb()
    {
        $qb = new CriteriaQueryBuilder($this->em->getRepository(Author::class));

        $dateTimeFilter = new OperatorFilter('birth');
        $dateTimeFilter->addOperator(new DateTimeOperator('from', OperatorInterface::OP_GTE));
        $dateTimeFilter->addOperator(new DateTimeOperator('to', OperatorInterface::OP_LTE));

        $qb->addFilter(new ScalarFilter('name'));
        $qb->addFilter($dateTimeFilter);

        $qb->setDefaultOrder(['birth' => Criteria::DESC]);

        return $qb;
    }

    public function getBookQb()
    {
        $qb = new CriteriaQueryBuilder($this->em->getRepository(Book::class));

        $qb->addFilter(new ScalarFilter('name'));
        $qb->addFilter(new BooleanFilter('published', 'published', BooleanFilter::CAST_STRINGS));

        return $qb;
    }

    /**
     * @test
     */
    public function it_returns_criteria()
    {
        $qb = $this->getAuthorQb();

        $criteria = $qb->getCriteria();

        self::assertEmpty($criteria->getWhereExpression());
        self::assertEquals(['birth' => 'DESC'], $criteria->getOrderings());

        $qb->setFilterParams([
            'birth' => ['from' => Carbon::now()->subDays(7)->format('U')]
        ]);

        $criteria = $qb->getCriteria();

        self::assertNotEmpty($criteria->getWhereExpression());
    }

    /**
     * @test
     */
    public function this_throw_exception_on_missing_default_order_field()
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionMessageRegExp('@has no field or association named created$@');
        $qb = $this->getAuthorQb();
        $qb->setDefaultOrder([]);

        $qb->getQb()->getQuery()->getSQL();
    }

    /**
     * @test
     */
    public function it_returns_correct_sql_on_datetime()
    {
        $qb = $this->getAuthorQb();

        $qb->setFilterParams([
            'birth' => ['from' => Carbon::now()->subDays(7)->format('U')]
        ]);

        $query = $qb->getQb()->getQuery();
        $this->assertSame(
            'SELECT a0_.name AS name_0, a0_.birth AS birth_1, a0_.id AS id_2 FROM Author a0_ WHERE a0_.birth >= ? ORDER BY a0_.birth DESC',
            $query->getSQL()
        );
        $this->assertCount(1, $query->getParameters());
    }

    /**
     * @test
     */
    public function it_returns_correct_sql_on_boolean()
    {
        $qb = $this->getBookQb();
        $qb->setDefaultOrder(['name' => Criteria::DESC]);

        $qb->setFilterParams([
            'published' => 'yes'
        ]);

        $query = $qb->getQb()->getQuery();
        $this->assertSame(
            'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.id AS id_2 FROM Book b0_ WHERE b0_.published = ? ORDER BY b0_.name DESC',
            $query->getSQL()
        );
        $this->assertCount(1, $query->getParameters());
        $this->assertTrue($query->getParameter('published')->getValue());
    }
}
