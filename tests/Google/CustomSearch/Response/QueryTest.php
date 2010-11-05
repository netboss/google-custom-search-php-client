<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Response/Query.php');

class Google_CustomSearch_Response_QueryTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $queryData = new stdClass();
        $queryData->count           = '1';
        $queryData->cref            = '2';
        $queryData->cx              = '3';
        $queryData->invalid_1       = '4';
        $queryData->inputEncoding   = '5';
        $queryData->language        = '6';
        $queryData->outputEncoding  = '7';
        $queryData->invalid_2       = '8';
        $queryData->safe            = '9';
        $queryData->searchTerms     = '10';
        $queryData->startIndex      = '11';
        $queryData->invalid_3       = '12';
        $queryData->startPage       = '13';
        $queryData->title           = '14';
        $queryData->totalResults    = '15';

        $query = new Google_CustomSearch_Response_Query($queryData);

        return $query;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Query $query)
    {
        $this->assertEquals('1', $query->getCount());
        $this->assertEquals('2', $query->getCustomSearchEngineSpecUrl());
        $this->assertEquals('3', $query->getCustomSearchEngineId());
        $this->assertEquals('5', $query->getInputEncoding());
        $this->assertEquals('6', $query->getLanguage());
        $this->assertEquals('7', $query->getOutputEncoding());
        $this->assertEquals('9', $query->getSafeMode());
        $this->assertEquals('10', $query->getSearchTerms());
        $this->assertEquals('11', $query->getStartIndex());
        $this->assertEquals('13', $query->getStartPage());
        $this->assertEquals('14', $query->getTitle());
        $this->assertEquals('15', $query->getTotalResults());
    }
}