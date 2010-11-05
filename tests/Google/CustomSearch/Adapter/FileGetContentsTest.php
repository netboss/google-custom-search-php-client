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
            $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', Google_CustomSearch_ErrorException::ADAPTER_FILE_GET_CONTENTS_EXECUTE_FAILED));
        }
        catch (Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::ADAPTER_FILE_GET_CONTENTS_EXECUTE_FAILED, $e->getCode());
        }

        $response = $adapter->executeRequest(self::getFixturesDir() . '/kind_missing.json');
        $this->assertEquals(file_get_contents(self::getFixturesDir() . '/kind_missing.json'), $response);
    }
}