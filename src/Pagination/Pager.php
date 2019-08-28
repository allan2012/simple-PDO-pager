<?php

namespace Pagination;

use \PDO;

/**
 * @author Allan Kibet Koskeu <allan.koskei@gmail.com>
 * @version 1.0.0
 */
class Pager
{

    private $perPage;

    private $totalPageCount;

    private $queryString;

    private $offset;

    private $currentPage;

    private $firstLink;

    private $nextLink;

    private $lastLink;

    private $backLink;

    private $pageURL;

    private $data;

    private $pdo;

    private $dataCount;

    private $queryParams;

    private $persistentParams;

    const FIRST_PAGE = 1;

    const DEFAULT_PER_PAGE = 10;


    /**
     * Initialize Pager
     *
     * @param PDO $pdo
     * @param string $queryString
     */
    public function __construct($pdo, $queryString)
    {
        $this->pdo = $pdo;
        $this->queryString = $queryString;
        $this->currentPage = $_GET['page'] ?? self::FIRST_PAGE ;
        $this->offset = 0;
        $this->pageURL = $_SERVER['REQUEST_URI'] ?? null;
        $this->queryParams = parse_url($this->pageURL, PHP_URL_QUERY);
        $this->data = [];
        $this->perPage = self::DEFAULT_PER_PAGE;
        $this->persistentParams = '';
    }

    /**
     * Initialize pager
     *
     * @return void
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


    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setPageURL($url)
    {
        $this->pageURL = $url;
        return $this;
    }

    /**
     * Setter for page offset
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
     * Setter for page URL
     */
    public function getPageURL() : string
    {
        return $this->pageURL;
    }

    /**
     * Setter for results count
     */
    private function setDataCount()
    {
        $this->dataCount = count($this->pdo->query($this->queryString)->fetchAll(PDO::FETCH_OBJ));
    }

    private function persistQueryParams() {
        $output = [];
        parse_str($this->queryParams, $output);
        unset($output['page']);

        if($output != []){
            $this->persistentParams = '&'.http_build_query($output);
        } 
    }

    public function getPersistentParams() {
        return $this->persistentParams;
    }

    /**
     * Setter for total page count
     */
    private function setTotalPageCount() 
    {
        $this->totalPageCount = ceil($this->dataCount / $this->perPage);
    }

    /**
     * Setter for query results data
     */
    private function setData()
    {
        $this->data = $this->pdo->query($this->queryString 
        . " LIMIT {$this->perPage} OFFSET {$this->offset}")->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Setter for next link
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
     * Setter for back link
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
     * Setter for first link
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
     * Setter for last link
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
     * Get paginated data and meta in array object
     *
     * @return object
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
     * Get paginated data and meta in JSON format
     *
     * @return string
     */
    public function paginateJSON() : string
    {
        return json_encode($this->paginate());
    }

}