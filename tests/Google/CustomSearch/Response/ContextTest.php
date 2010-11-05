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

        $contextData = new stdClass();
        $contextData->invalid_1 = '1';
        $contextData->title = '2';
        $contextData->invalid_2 = '3';
        $contextData->facets = array(
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

        $context = new Google_CustomSearch_Response_Context($contextData);

        return $context;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Context $context)
    {
        $this->assertEquals('2', $context->getTitle());
        $this->assertTrue($context->hasFacets());
        $this->assertType('array', $context->getFacets());
        $this->assertEquals(2, count($context->getFacets()));
    }
}