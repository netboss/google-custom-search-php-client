<?php

require_once(dirname(__FILE__).'/../../../../../../src/Google/CustomSearch/Response/Item/PageMap/DataObject.php');

class Google_CustomSearch_Response_Item_PageMap_DataObjectTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $resultData = new stdClass();
        $resultData->invalid_1 = '1';
        $resultData->property_1 = '2';
        $resultData->invalid_2 = '3';
        $resultData->property_2 = '4';
        $resultData->invalid_3 = '5';
        $resultData->property_3 = array();

        $result = new Google_CustomSearch_Response_Item_PageMap_DataObject($resultData);

        return $result;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Item_PageMap_DataObject $result)
    {
        $this->assertEquals('2', $result->getProperty('property_1'));
        $this->assertEquals('4', $result->getProperty('property_2'));
        $this->assertEquals('1', $result->getProperty('invalid_1'));
        $this->assertEquals(array(), $result->getProperty('property_3'));
    }
}