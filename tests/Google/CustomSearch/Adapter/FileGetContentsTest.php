<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Adapter/FileGetContents.php');

class Google_CustomSearch_Adapter_FileGetContentsTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Static methods
    // ------------------------------------------------------

    public static function getFixturesDir()
    {
        return dirname(__FILE__) . '/../Fixtures/';
    }

    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testExecuteRequest()
    {
        $adapter = new Google_CustomSearch_Adapter_FileGetContents();

        try
        {
            $adapter->executeRequest(self::getFixturesDir() . '/invalid');
            $this->fail('Excepted exception "RuntimeException" not thrown, invalid request URL.');
        }
        catch (RuntimeException $e) {}

        $response = $adapter->executeRequest(self::getFixturesDir() . '/kind_missing.json');
        $this->assertEquals(file_get_contents(self::getFixturesDir() . '/kind_missing.json'), $response);
    }
}