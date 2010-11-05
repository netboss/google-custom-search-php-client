<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Response/Result.php');

class Google_CustomSearch_Response_ResultTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $resultData = new stdClass();

        try
        {
            $result = new Google_CustomSearch_Response_Result($resultData);
            $this->fail('Excepted exception "InvalidArgumentException" not thrown, invalid result "kind".');
        }
        catch(Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::ITEM_KIND_INVALID, $e->getCode());
        }

        $resultData = new stdClass();
        $resultData->kind = 'invalid';

        try
        {
            $result = new Google_CustomSearch_Response_Result($resultData);
            $this->fail('Excepted exception "InvalidArgumentException" not thrown, invalid result "kind".');
        }
        catch(Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::ITEM_KIND_INVALID, $e->getCode());
        }

        $dataObject = new stdClass();
        $dataObject->property1 = '1';
        $dataObject->property2 = '2';

        $pageMap = new stdClass();
        $pageMap->test1 = array(
            $dataObject
        );
        $pageMap->test2 = array(
            $dataObject
        );
        $pageMap->invalid1 = array();
        $pageMap->test3 = array(
            $dataObject
        );
        $pageMap->invalid2 = array(
            1 => $dataObject
        );

        $resultData = new stdClass();
        $resultData->kind = Google_CustomSearch_Response_Result::KIND;
        $resultData->displayLink    = '1';
        $resultData->invalid_1      = '2';
        $resultData->htmlSnippet    = '3';
        $resultData->htmlTitle      = '4';
        $resultData->link           = '5';
        $resultData->invalid_2      = '6';
        $resultData->snippet        = '7';
        $resultData->invalid_3      = '8';
        $resultData->title          = '9';
        $resultData->pagemap        = $pageMap;

        $result = new Google_CustomSearch_Response_Result($resultData);

        return $result;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Result $result)
    {
        $this->assertEquals('1', $result->getDisplayLink());
        $this->assertEquals('3', $result->getHtmlSnippet());
        $this->assertEquals('4', $result->getHtmlTitle());
        $this->assertEquals('5', $result->getLink());
        $this->assertEquals('7', $result->getSnippet());
        $this->assertEquals('9', $result->getTitle());

        $this->assertType('Google_CustomSearch_Response_Result_PageMap', $result->getPageMap());
        $this->assertTrue($result->getPageMap()->hasDataObjects());
        $this->assertEquals(3, count($result->getPageMap()->getDataObjects()));
    }
}