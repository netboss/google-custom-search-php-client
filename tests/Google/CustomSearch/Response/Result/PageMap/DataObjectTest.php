<?php

require_once(dirname(__FILE__).'/../../../../../../src/Google/CustomSearch/Response/Result/PageMap/DataObject.php');

class Google_CustomSearch_Response_Result_PageMap_DataObjectTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $dataObjectData = new stdClass();
        $dataObjectData->invalid_1 = '1';
        $dataObjectData->property_1 = '2';
        $dataObjectData->invalid_2 = '3';
        $dataObjectData->property_2 = '4';
        $dataObjectData->invalid_3 = '5';
        $dataObjectData->property_3 = array();

        $dataObject = new Google_CustomSearch_Response_Result_PageMap_DataObject($dataObjectData);

        return $dataObject;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Result_PageMap_DataObject $dataObject)
    {
        $this->assertEquals('2', $dataObject->getProperty('property_1'));
        $this->assertEquals('4', $dataObject->getProperty('property_2'));
        $this->assertEquals('1', $dataObject->getProperty('invalid_1'));
        $this->assertEquals(array(), $dataObject->getProperty('property_3'));
    }
}