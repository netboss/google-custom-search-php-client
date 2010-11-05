<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Response/Item.php');

class Google_CustomSearch_Response_ItemTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $itemData = new stdClass();

        try
        {
            $item = new Google_CustomSearch_Response_Item($itemData);
            $this->fail('Excepted exception "InvalidArgumentException" not thrown, invalid item "kind".');
        }
        catch(RuntimeException $e)
        {
            $this->assertTrue(true);
        }

        $itemData = new stdClass();
        $itemData->kind = 'invalid';

        try
        {
            $item = new Google_CustomSearch_Response_Item($itemData);
            $this->fail('Excepted exception "InvalidArgumentException" not thrown, invalid item "kind".');
        }
        catch(RuntimeException $e)
        {
            $this->assertTrue(true);
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

        $itemData = new stdClass();
        $itemData->kind = Google_CustomSearch_Response_Item::KIND;
        $itemData->displayLink    = '1';
        $itemData->invalid_1      = '2';
        $itemData->htmlSnippet    = '3';
        $itemData->htmlTitle      = '4';
        $itemData->link           = '5';
        $itemData->invalid_2      = '6';
        $itemData->snippet        = '7';
        $itemData->invalid_3      = '8';
        $itemData->title          = '9';
        $itemData->pagemap        = $pageMap;

        $item = new Google_CustomSearch_Response_Item($itemData);

        return $item;
    }

    /**
     * @depends testConstruct
     */
    public function testGenericGetters(Google_CustomSearch_Response_Item $item)
    {
        $this->assertEquals('1', $item->getDisplayLink());
        $this->assertEquals('3', $item->getHtmlSnippet());
        $this->assertEquals('4', $item->getHtmlTitle());
        $this->assertEquals('5', $item->getLink());
        $this->assertEquals('7', $item->getSnippet());
        $this->assertEquals('9', $item->getTitle());

        $this->assertType('Google_CustomSearch_Response_Item_PageMap', $item->getPageMap());
        $this->assertTrue($item->getPageMap()->hasDataObjects());
        $this->assertEquals(3, count($item->getPageMap()->getDataObjects()));
    }
}