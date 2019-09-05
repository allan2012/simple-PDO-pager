<?php

namespace Pagination;

/**
 * 
 * @author Allan Kibet Koskei <allan.koskei@gmail.com>
 * 
 * @version 1.0.0
 * 
 */
class Pager
{

    /**
     * 
     * Items per page
     *
     * @var int
     * 
     */
    private $perPage;


    /**
     * 
     * Total page count
     *
     * @var int
     * 
     */
    private $totalPageCount;


    /**
     * 
     * Query to be paginated
     *
     * @var string
     * 
     */
    private $queryString;


    /**
     * 
     * SQL Query offset
     *
     * @var int
     * 
     */
    private $offset;


    /**
     * 
     * Current page number
     *
     * @var int
     * 
     */
    private $currentPage;


    /**
     * 
     * First page link
     *
     * @var any
     * 
     */
    private $firstLink;


    /**
     * 
     * Next page link
     *
     * @var any
     * 
     */
    private $nextLink;


    /**
     * 
     * Final page link
     *
     * @var any
     * 
     */
    private $lastLink;


    /**
     * 
     * Previous page link
     *
     * @var any
     * 
     */
    private $backLink;


    /**
     * 
     * Current URL
     *
     * @var string
     * 
     */
    private $pageURL;


    /**
     * 
     * Paginated data
     *
     * @var array
     * 
     */
    private $data;


    /**
     * 
     * PDO Object
     *
     * @var any
     * 
     */
    private $pdo;


    /**
     * 
     * Total data count
     *
     * @var int
     * 
     */
    private $dataCount;


    /**
     * 
     * URL query params
     *
     * @var array
     * 
     */
    private $queryParams;


    /**
     * 
     * formulated URL query string
     *
     * @var string
     * 
     */
    private $persistentParams;


    const FIRST_PAGE = 1;
    const DEFAULT_PER_PAGE = 10;


    /**
     * 
     * Initialize Pager
     *
     * @param PDO $pdo
     * 
     * @param string $queryString
     * 
     * @param array $paginationParams optional
     * 
     */
    public function __construct($pdo, $queryString)
    {
        $this->pdo = $pdo;
        $this->queryString = $queryString;
        $this->currentPage = $_GET['page'] ?? self::FIRST_PAGE ;
        $this->offset = 0;
        $this->pageURL = $_SERVER['REQUEST_URI'];
        $this->queryParams = parse_url($this->pageURL, PHP_URL_QUERY);
        $this->data = [];
        $this->perPage = self::DEFAULT_PER_PAGE;
        $this->persistentParams = '';
    }


    /**
     * 
     * Initialize pager
     *
     * @return void
     * 
     */
    public function initialize()
    {
        $this->setDataCount();
        $this->setTotalPageCount();
        $this->setOffset();
        $this->persistQueryParams();
        $this->setFirstLink();
        $this->setBackLink();
        $this->setNextLink();
        $this->setLastLink();
        $this->setData();
        return $this;
    }


    public function setPerPage($perPage)
    {
        $this->perPage = (int)$perPage;
        return $this;
    }


    /**
     * 
     * Setter for page URL
     *
     * @param string $url
     * 
     * @return
     *  
     */
    public function setPageURL($url)
    {
        $this->pageURL = $url;
        return $this;
    }


    /**
     * 
     * Setter for page offset
     * 
     * @return void
     * 
     */
    private function setOffset() 
    {
        if ($this->currentPage == 1) {
            $this->offset = $this->currentPage - 1;
        } else {
            $this->offset = ($this->currentPage - 1) * $this->perPage;
        }
    }


    /**
     * 
     * Setter for page URL
     * 
     * @return string
     * 
     */  
    public function getPageURL() : string
    {
        return $this->pageURL;
    }


    /**
     * 
     * Setter for results count
     * 
     * @return void
     * 
     */
    private function setDataCount()
    {
        $this->dataCount = count($this->pdo->query($this->queryString)->fetchAll(PDO::FETCH_OBJ));
    }


    /**
     * 
     * Persist query params to be appended to the paginated links
     *
     * @return void
     * 
     */
    private function persistQueryParams() 
    {
        $output = [];
        parse_str($this->queryParams, $output);
        unset($output['page']);

        if($output != []){
            $this->persistentParams = '&'.http_build_query($output);
        } 
    }


    /**
     * 
     * Setter for total page count
     * 
     * @return void
     * 
     */
    private function setTotalPageCount() 
    {
        $this->totalPageCount = ceil($this->dataCount / $this->perPage);
    }


    /**
     * 
     * Setter for query results data
     * 
     * @return void
     * 
     */
    private function setData()
    {
        $this->data = $this->pdo->query($this->queryString 
        . " LIMIT {$this->perPage} OFFSET {$this->offset}")->fetchAll(PDO::FETCH_OBJ);
    }


    /**
     * 
     * Setter for next link
     * 
     * @return void
     * 
     */
    private function setNextLink() 
    {
        $assumedNextpage = $this->currentPage + 1;
        if ($assumedNextpage > $this->totalPageCount) {
            $this->nextLink = null;
        } else {
            $nextPage = $this->currentPage + 1;
            $this->nextLink = '?page='.$nextPage.$this->persistentParams;
        }
    }


    /**
     * 
     * Setter for back link
     * 
     * @return void
     * 
     */
    private function setBackLink()
    {
        if ($this->currentPage < 2) {
            $this->backLink = null;
        } else {
            $backPage = $this->currentPage - 1;
            $this->backLink = '?page='.$backPage.$this->persistentParams;
        }
    }


    /**
     * 
     * Setter for first link
     * 
     * @return void
     * 
     */
    private function setFirstLink()
    {
        if ($this->currentPage == 1) {
            $this->firstLink = null;
        } else {
            $this->firstLink = '?page=1'.$this->persistentParams;
        }
    }


    /**
     * 
     * Setter for last link
     * 
     * @return void
     * 
     */
    private function setLastLink()
    {
        if($this->currentPage == $this->totalPageCount) {
            $this->lastLink = null;
        } else {
            $this->lastLink = '?page='.$this->totalPageCount.$this->persistentParams;
        }
    }


    /**
     * 
     * Get paginated data and meta in array object
     *
     * @return object
     * 
     */
    public function paginate() : object 
    {
        return (object)[
            'firstLink' => $this->firstLink,
            'backLink' => $this->backLink,
            'nextLink' => $this->nextLink,
            'lastLink' => $this->lastLink,
            'currentPage' => (int) $this->currentPage,          
            'totalPageCount' => (int) $this->totalPageCount,
            'recordsCount' => (int) $this->dataCount,
            'pageURL' => $this->getPageURL(),
            'data' => $this->data
        ];
    }


    /**
     * 
     * Get paginated data and meta in JSON format
     *
     * @return string
     * 
     */
    public function paginateJson() : string
    {
        return json_encode($this->paginate());
    }

}
