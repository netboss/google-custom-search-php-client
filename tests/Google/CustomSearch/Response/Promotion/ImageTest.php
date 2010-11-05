<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Promotion/Image.php');

class Google_CustomSearch_Response_Promotion_ImageTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $imageData = new stdClass();
        $imageData->invalid_1 = '1';
        $imageData->height = '2';
        $imageData->invalid_2 = '3';
        $imageData->source = '4';
        $imageData->invalid_3 = '5';
        $imageData->width = '6';

        $image = new Google_CustomSearch_Response_Promotion_Image($imageData);

        return $image;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Promotion_Image $image)
    {
        $this->assertEquals(2, $image->getHeight());
        $this->assertEquals('4', $image->getSource());
        $this->assertEquals(6, $image->getWidth());
    }

    public function testIntegerProperties()
    {
        $imageData = new stdClass();
        $imageData->height = '1';
        $imageData->width = '2.00';
        $image = new Google_CustomSearch_Response_Promotion_Image($imageData);

        $this->assertTrue($image->getHeight() === 1);
        $this->assertTrue($image->getWidth() === 2);
    }
}