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

        $pageMapData = new stdClass();
        $pageMapData->test1 = array(
            $dataObject
        );
        $pageMapData->invalid_1 = $dataObject;
        $pageMapData->test2 = array(
            $dataObject
        );
        $pageMapData->invalid_2 = array();
        $pageMapData->invalid_3 = array(
            1 => $dataObject
        );

        $pageMap = new Google_CustomSearch_Response_Item_PageMap($pageMapData);

        return $pageMap;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Item_PageMap $pageMap)
    {
        $this->assertTrue($pageMap->hasDataObjects());
        $this->assertType('array', $pageMap->getDataObjects());
        $this->assertEquals(2, count($pageMap->getDataObjects()));
        foreach($pageMap->getDataObjects() as $dataObject)
        {
            $this->assertType('Google_CustomSearch_Response_Item_PageMap_DataObject', $dataObject);
        }

        $this->assertNull($pageMap->getDataObject('invalid_1'));
        $this->assertType('Google_CustomSearch_Response_Item_PageMap_DataObject', $pageMap->getDataObject('test2'));
    }
}