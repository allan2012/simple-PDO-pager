<?php

use PHPUnit\Framework\TestCase;
use Pagination\Pager;
use \PDO;

Class PaginationTest extends TestCase
{

    private static $pdoConnection;

    private static $p;

    static function setUpBeforeClass()
    {
        self::$pdoConnection = new \PDO("mysql:host=localhost;port=3306;dbname=labstore", 'root', '');
        self::$p = new Pager(self::$pdoConnection, "SELECT * FROM inventory");
    }

    function setUp()
    {
    }

    public function testSetPageURL()
    {
        self::$p->setPageUrl("http://localhost/inventory");
        $this->assertEquals("http://localhost/inventory", self::$p->getPageURL());
    }

    public function testSetPerPage()
    {
        self::$p->setPerPage(12);
        $this->assertSame(12, self::$p->getPerPage());
    }

    public function testPagerPaginateReturnsValidObject()
    {
        self::$p->setPerPage(5);
        self::$p->setPageUrl("http://localhost/inventory");
        self::$p->initialize();
        $meta = self::$p->paginate();
        $this->assertIsObject($meta);
    }

    public function testPagerPaginateJSONreturnsValidJsonString()
    {
        self::$p->setPageUrl("http://localhost/inventory");
        self::$p->setPerPage(5);
        self::$p->initialize();
        $meta = self::$p->paginateJSON();
        $this->assertJson($meta);
    }

    public function testTotalPageCountValue()
    {
        self::$p->setPageUrl("http://localhost/inventory");
        self::$p->setPerPage(10);
        self::$p->initialize();
        $meta = self::$p->paginate();
        $this->assertEquals(50, $meta->totalPageCount);
    }

    public function testFirstLinkIsNullOnFirstPage()
    {
        self::$p->setPageUrl("http://localhost/inventory");
        self::$p->setPerPage(10);
        self::$p->initialize();
        $meta = self::$p->paginate();
        $this->assertEquals(null, $meta->firstLink);
    }

    public function testLastLinkIsNullOnLastPage() {
        self::$p->setPageUrl("http://localhost/inventory?page=55");
        self::$p->setPerPage(10);
        self::$p->initialize();
        $meta = self::$p->paginate();
        $this->assertEquals(null, $meta->lastLink);
    }

    // Clean up the test case, called for every defined test
    public function tearDown()
    {
        self::$pdoConnection = null;
    }


}
