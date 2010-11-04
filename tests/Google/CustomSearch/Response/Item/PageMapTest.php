<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Item/PageMap.php');

class Google_CustomSearch_Response_Item_PageMapTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $dataObject = new stdClass();
        $dataObject->property1 = '1';
        $dataObject->property2 = '2';

        $resultData = new stdClass();
        $resultData->test1 = array(
            $dataObject
        );
        $resultData->invalid_1 = $dataObject;
        $resultData->test2 = array(
            $dataObject
        );
        $resultData->invalid_2 = array();
        $resultData->invalid_3 = array(
            1 => $dataObject
        );

        $result = new Google_CustomSearch_Response_Item_PageMap($resultData);

        return $result;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Item_PageMap $result)
    {
        $this->assertTrue($result->hasDataObjects());
        $this->assertType('array', $result->getDataObjects());
        $this->assertEquals(2, count($result->getDataObjects()));
    }
}