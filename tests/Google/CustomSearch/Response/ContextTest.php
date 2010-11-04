<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Response/Context.php');

class Google_CustomSearch_Response_ContextTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $facet = new stdClass();
        $facet->anchor = '1';
        $facet->label = '2';

        $resultData = new stdClass();
        $resultData->invalid_1 = '1';
        $resultData->title = '2';
        $resultData->invalid_2 = '3';
        $resultData->facets = array(
            true,
            'invalid',
            array(
                $facet
            ),
            array(
                $facet
            ),
            array(),
            array(
                1 => $facet
            )
        );

        $result = new Google_CustomSearch_Response_Context($resultData);

        return $result;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Context $result)
    {
        $this->assertEquals('2', $result->getTitle());
        $this->assertTrue($result->hasFacets());
        $this->assertType('array', $result->getFacets());
        $this->assertEquals(2, count($result->getFacets()));
    }
}