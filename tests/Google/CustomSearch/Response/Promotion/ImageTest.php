<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Promotion/Image.php');

class Google_CustomSearch_Response_Promotion_ImageTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $resultData = new stdClass();
        $resultData->invalid_1 = '1';
        $resultData->height = '2';
        $resultData->invalid_2 = '3';
        $resultData->source = '4';
        $resultData->invalid_3 = '5';
        $resultData->width = '6';

        $result = new Google_CustomSearch_Response_Promotion_Image($resultData);

        return $result;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Promotion_Image $result)
    {
        $this->assertEquals(2, $result->getHeight());
        $this->assertEquals('4', $result->getSource());
        $this->assertEquals(6, $result->getWidth());
    }

    public function testIntegerProperties()
    {
        $resultData = new stdClass();
        $resultData->height = '1';
        $resultData->width = '2.00';
        $result = new Google_CustomSearch_Response_Promotion_Image($resultData);

        $this->assertTrue($result->getHeight() === 1);
        $this->assertTrue($result->getWidth() === 2);
    }
}