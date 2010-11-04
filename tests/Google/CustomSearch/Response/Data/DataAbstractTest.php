<?php

require_once(dirname(__FILE__).'/../../../../../src/Google/CustomSearch/Response/Data/DataAbstract.php');

class Google_CustomSearch_Response_DataAbstractTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Static methods
    // ------------------------------------------------------

    public static function getResponseDataFixture1()
    {
        $resultData = new stdClass();
        $resultData->test1 = 'foo';
        $resultData->test2 = 'bar';

        return $resultData;
    }

    public static function getResponseDataFixture2()
    {
        $resultData = new stdClass();
        $resultData->test3 = 'bill';
        $resultData->test4 = array('ted', 'bob');

        return $resultData;
    }

    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testGetPropertyFromResponseData()
    {
        $resultData = new stdClass();
        $resultData->test1 = 'foo';
        $resultData->test2 = 'bar';

        $this->assertNull(Google_CustomSearch_Response_DataAbstract::getPropertyFromResponseData(true, $resultData));
        $this->assertNull(Google_CustomSearch_Response_DataAbstract::getPropertyFromResponseData(1, $resultData));

        $this->assertNull(Google_CustomSearch_Response_DataAbstract::getPropertyFromResponseData('notfound', $resultData));
        $this->assertEquals('foo', Google_CustomSearch_Response_DataAbstract::getPropertyFromResponseData('test1', $resultData));
        $this->assertEquals('bar', Google_CustomSearch_Response_DataAbstract::getPropertyFromResponseData('test2', $resultData));
    }

    public function testToArray()
    {
        $dataFixture2 = new stdClass();
        $dataFixture2->test3 = 'bill';
        $dataFixture2->test4 = array('ted', 'bob');

        $dataStub2 = new Google_CustomSearch_Response_DataAbstractStub2($dataFixture2);

        $dataFixture1 = new stdClass();
        $dataFixture1->test1 = 'foo';
        $dataFixture1->test2 = $dataStub2;

        $dataStub1 = new Google_CustomSearch_Response_DataAbstractStub1($dataFixture1);

        $this->assertEquals(array('test1' => 'foo', 'test2' => $dataStub2), $dataStub1->toArray());
        $this->assertEquals(array('test1' => 'foo', 'test2' => array('test3' => 'bill', 'test4' => array('ted', 'bob'))), $dataStub1->toArray(true));
    }

    public function testParseStandardProperties()
    {
        try
        {
            $dataFixture = new stdClass();
            $dataFixture->test5 = 'foo';
            $dataFixture->test6 = 'bar';

            $dataStub = new Google_CustomSearch_Response_DataAbstractStub3($dataFixture);
            $this->fail('InvalidArgumentException should be thrown due to invalid data object property "test6".');
        }
        catch(InvalidArgumentException $e)
        {
            $this->assertTrue(true);
        }
    }
}

class Google_CustomSearch_Response_DataAbstractStub1 extends Google_CustomSearch_Response_DataAbstract
{
    protected $test1;
    protected $test2;

    protected function parse(stdClass $resultData)
    {
        $this->parseStandardProperties($resultData, array(
            'test1',
            'test2'
        ));
    }
}

class Google_CustomSearch_Response_DataAbstractStub2 extends Google_CustomSearch_Response_DataAbstract
{
    protected $test3;
    protected $test4;

    protected function parse(stdClass $resultData)
    {
        $this->parseStandardProperties($resultData, array(
            'test3',
            'test4'
        ));
    }
}

class Google_CustomSearch_Response_DataAbstractStub3 extends Google_CustomSearch_Response_DataAbstract
{
    protected $test5;

    protected function parse(stdClass $resultData)
    {
        $this->parseStandardProperties($resultData, array(
            'test5',
            'test6'
        ));
    }
}