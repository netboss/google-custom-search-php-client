<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Promotion/BodyLine.php');

class Google_CustomSearch_Response_Promotion_BodyLineTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $bodyLineData = new stdClass();
        $bodyLineData->invalid_1 = '1';
        $bodyLineData->link = '2';
        $bodyLineData->invalid_2 = '3';
        $bodyLineData->title = '4';
        $bodyLineData->invalid_3 = '5';
        $bodyLineData->url = '6';

        $bodyLine = new Google_CustomSearch_Response_Promotion_BodyLine($bodyLineData);

        return $bodyLine;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Promotion_BodyLine $bodyLine)
    {
        $this->assertEquals('2', $bodyLine->getLink());
        $this->assertEquals('4', $bodyLine->getTitle());
        $this->assertEquals('6', $bodyLine->getUrl());
    }
}