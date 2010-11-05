<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Context/Facet.php');

class Google_CustomSearch_Response_Context_FacetTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $facetData = new stdClass();
        $facetData->invalid_1 = '1';
        $facetData->anchor = '2';
        $facetData->invalid_2 = '3';
        $facetData->label = '4';
        $facetData->invalid_3 = '5';

        $facet = new Google_CustomSearch_Response_Context_Facet($facetData);

        return $facet;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Context_Facet $facet)
    {
        $this->assertEquals('2', $facet->getAnchor());
        $this->assertEquals('4', $facet->getLabel());
    }
}