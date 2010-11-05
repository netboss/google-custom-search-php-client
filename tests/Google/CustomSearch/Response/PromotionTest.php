<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Response/Promotion.php');

class Google_CustomSearch_Response_PromotionTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $bodyLinesData = new stdClass();
        $bodyLinesData->invalid_1 = '1';
        $bodyLinesData->link = '2';
        $bodyLinesData->invalid_2 = '3';
        $bodyLinesData->title = '4';
        $bodyLinesData->invalid_3 = '5';
        $bodyLinesData->url = '6';

        $imageData = new stdClass();
        $imageData->invalid_1 = '1';
        $imageData->height = '2';
        $imageData->invalid_2 = '3';
        $imageData->source = '4';
        $imageData->invalid_3 = '5';
        $imageData->width = '6';
        
        $promotionData = new stdClass();
        $promotionData->displayLink = '1';
        $promotionData->invalid_1   = '2';
        $promotionData->link        = '3';
        $promotionData->invalid_2   = '4';
        $promotionData->title       = '5';
        $promotionData->invalid_3   = '6';
        $promotionData->bodyLines   = array($bodyLinesData, true, false, array(), $bodyLinesData);
        $promotionData->invalid_4   = '7';
        $promotionData->image       = $imageData;

        $promotion = new Google_CustomSearch_Response_Promotion($promotionData);

        return $promotion;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Promotion $promotion)
    {
        $this->assertEquals('1', $promotion->getDisplayLink());
        $this->assertEquals('3', $promotion->getLink());
        $this->assertEquals('5', $promotion->getTitle());

        $this->assertTrue($promotion->hasBodyLine());
        $this->assertType('array', $promotion->getBodyLine());
        $this->assertEquals(2, count($promotion->getBodyLine()));
        foreach($promotion->getBodyLine() as $bodyLine)
        {
            $this->assertType('Google_CustomSearch_Response_Promotion_BodyLine', $bodyLine);
        }

        $this->assertType('Google_CustomSearch_Response_Promotion_Image', $promotion->getImage());
    }
}