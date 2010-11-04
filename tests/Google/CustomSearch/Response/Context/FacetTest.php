<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Context/Facet.php');

class Google_CustomSearch_Response_Context_FacetTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $resultData = new stdClass();
        $resultData->invalid_1 = '1';
        $resultData->anchor = '2';
        $resultData->invalid_2 = '3';
        $resultData->label = '4';
        $resultData->invalid_3 = '5';

        $result = new Google_CustomSearch_Response_Context_Facet($resultData);

        return $result;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Context_Facet $result)
    {
        $this->assertEquals('2', $result->getAnchor());
        $this->assertEquals('4', $result->getLabel());
    }
}