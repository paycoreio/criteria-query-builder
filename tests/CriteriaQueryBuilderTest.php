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
use Paymaxi\Component\Query\Filter\EnumerationFilter;
use Paymaxi\Component\Query\Filter\OperatorFilter;
use Paymaxi\Component\Query\Filter\ScalarFilter;
use Paymaxi\Component\Query\Operator\DateTimeOperator;
use Paymaxi\Component\Query\Operator\OperatorInterface;
use Paymaxi\Component\Query\Sort\StaticSorting;
use Paymaxi\Component\Query\Tests\Entity\Author;
use Paymaxi\Component\Query\Tests\Entity\Book;
use Paymaxi\Component\Query\Tests\Entity\BookWithCreated;
use PHPUnit\Framework\TestCase;

class CriteriaQueryBuilderTest extends TestCase
{
    /** @var SchemaTool */
    protected $schemaTool;

    /** @var EntityManager */
    private $em;

    public const DEFAULT_ORDER_FIELD = CriteriaQueryBuilder::DEFAULT_ORDER_FIELD;

    public function setUp(): void
    {
        $connectionParams = ['url' => 'sqlite://:memory:'];
        $config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/mapping"), true);
        $connection = DriverManager::getConnection($connectionParams, $config);
        $this->em = EntityManager::create($connection, $config);

        $this->schemaTool = new SchemaTool($this->em);
        $this->schemaTool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    public function tearDown(): void
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

    public function getBookQb(bool $withCreated = false)
    {
        if($withCreated) {
            $qb = new CriteriaQueryBuilder($this->em->getRepository(BookWithCreated::class));
        }
        else {
            $qb = new CriteriaQueryBuilder($this->em->getRepository(Book::class));
        }

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
    public function check_if_correct_changing_default_order()
    {
        $qb = $this->getAuthorQb();
        $qb->setDefaultOrder([]);

        $sql = $qb->getQb()->getQuery()->getSQL();
        self::assertStringNotContainsString('ORDER BY', $sql, 'The query contains ORDER BY statement, but default order is empty');
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
        self::assertSame(
            'SELECT a0_.name AS name_0, a0_.birth AS birth_1, a0_.id AS id_2 FROM Author a0_ WHERE a0_.birth >= ? ORDER BY a0_.birth DESC',
            $query->getSQL()
        );
        self::assertCount(1, $query->getParameters());
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
        self::assertSame(
            'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.description AS description_2, b0_.id AS id_3 FROM Book b0_ WHERE b0_.published = ? ORDER BY b0_.name DESC',
            $query->getSQL()
        );
        self::assertCount(1, $query->getParameters());
        self::assertTrue($query->getParameter('published')->getValue());
    }

    public function test_default_order_if_entity_not_contains_created_field()
    {
        $qb = $this->getBookQb();
        $sql = $qb->getQb()->getQuery()->getSQL();
        self::assertStringNotContainsString('ORDER BY', $sql);
    }

    public function test_default_order_if_entity_contains_created_field()
    {
        $qb = $this->getBookQb(true);
        $qb->addSorting(new StaticSorting(static::DEFAULT_ORDER_FIELD));
        $sql = $qb->getQb()->getQuery()->getSQL();
        self::assertStringContainsString('ORDER BY b0_.created DESC', $sql);
    }

    public function test_default_order_if_default_order_already_exists()
    {
        $qb = $this->getBookQb(true);
        $qb->setDefaultOrder(['name' => 'DESC']);
        $qb->addSorting(new StaticSorting(static::DEFAULT_ORDER_FIELD));
        $sql = $qb->getQb()->getQuery()->getSQL();
        self::assertStringContainsString('ORDER BY b0_.name DESC, b0_.created DESC', $sql);
    }

    public function test_default_order_if_default_order_already_exists_and_entity_not_contains_created_field()
    {
        $qb = $this->getBookQb();
        $qb->setDefaultOrder(['name' => 'DESC']);
        $sql = $qb->getQb()->getQuery()->getSQL();
        self::assertStringContainsString('ORDER BY b0_.name DESC', $sql);
        self::assertStringNotContainsString('b0_.'.static::DEFAULT_ORDER_FIELD.' DESC', $sql);
    }

    public function test_default_order_if_default_order_already_contains_created_field()
    {
        $qb = $this->getBookQb(true);
        $qb->setDefaultOrder([static::DEFAULT_ORDER_FIELD => 'DESC']);
        $qb->addSorting(new StaticSorting(static::DEFAULT_ORDER_FIELD));
        $sql = $qb->getQb()->getQuery()->getSQL();
        $compareSql = 'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.description AS description_2, b0_.id AS id_3, b0_.created AS ' .
        'created_4 FROM BookWithCreated b0_ ORDER BY b0_.'.static::DEFAULT_ORDER_FIELD.' DESC';
        self::assertEquals($compareSql, $sql);
    }

    public function test_default_order_if_order_by_created_already_exists_in_query()
    {
        $qb = $this->getBookQb(true);
        $qb->addSorting(new StaticSorting(static::DEFAULT_ORDER_FIELD));
        $qb->setSortingFields([static::DEFAULT_ORDER_FIELD => 'ASC']);
        $sql = $qb->getQb()->getQuery()->getSQL();
        $compareSql = 'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.description AS description_2, b0_.id AS id_3, b0_.created AS '.
            'created_4 FROM BookWithCreated b0_ ORDER BY b0_.'.static::DEFAULT_ORDER_FIELD.' ASC';
        self::assertEquals($compareSql, $sql);
    }

    public function test_default_order_if_order_by_created_already_exists_in_query_and_in_default_order()
    {
        $qb = $this->getBookQb(true);
        $qb->addSorting(new StaticSorting(static::DEFAULT_ORDER_FIELD));
        $qb->setDefaultOrder([static::DEFAULT_ORDER_FIELD => 'DESC']);
        $qb->setSortingFields([static::DEFAULT_ORDER_FIELD => 'ASC']);
        $sql = $qb->getQb()->getQuery()->getSQL();
        $compareSql = 'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.description AS description_2, b0_.id AS id_3, b0_.created AS '.
            'created_4 FROM BookWithCreated b0_ ORDER BY b0_.'.static::DEFAULT_ORDER_FIELD.' ASC';
        self::assertEquals($compareSql, $sql);
    }

    public function test_scalar_filter_with_inverse_nullable_fields(): void
    {
        $qb = $this->getBookQb(true);
        $qb->addFilter(new ScalarFilter('description'));
        $qb->addFilter(new ScalarFilter('name'));

        $qb->setFilterParams([
            'description' => '-test',
            'name' => 'test',
        ]);

        $expectedSql = 'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.description AS description_2, b0_.id AS id_3, b0_.created AS '.
        'created_4 FROM BookWithCreated b0_ WHERE ((b0_.description <> ? OR b0_.description IS NULL) AND b0_.name = ?) AND b0_.name = ?';

        self::assertEquals($expectedSql, $qb->getQb()->getQuery()->getSQL());
    }

    public function test_enumeration_filter_with_inverse_nullable_fields(): void
    {
        $qb = $this->getBookQb(true);

        $qb->addFilter(new EnumerationFilter('description'));
        $qb->addFilter(new ScalarFilter('name'));
        $qb->setFilterParams([
            'description' => '-test',
            'name' => 'test',
        ]);

        $expectedSql = 'SELECT b0_.name AS name_0, b0_.published AS published_1, b0_.description AS description_2, b0_.id AS id_3, b0_.created AS ' .
        'created_4 FROM BookWithCreated b0_ WHERE ((b0_.description NOT IN (?) OR b0_.description IS NULL) AND b0_.name = ?) AND b0_.name = ?';

        self::assertEquals($expectedSql, $qb->getQb()->getQuery()->getSQL());
    }
}
