<?php

require_once(dirname(__FILE__).'/../../../../src/Google/CustomSearch/Adapter/Curl.php');

class Google_CustomSearch_Adapter_CurlTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testExecuteRequest()
    {
        $adapter = new Google_CustomSearch_Adapter_Curl();

        try
        {
            $adapter->executeRequest('');
            $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', Google_CustomSearch_ErrorException::ADAPTER_CURL_EXECUTE_FAILED));
        }
        catch (Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::ADAPTER_CURL_EXECUTE_FAILED, $e->getCode());
        }

        $response = $adapter->executeRequest('http://www.google.co.uk/');
        $this->assertType('string', $response);
        $this->assertTrue(strlen(trim($response)) > 0);
    }
}