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
            $adapter->executeRequest('invalid');
            $this->fail('Excepted exception "RuntimeException" not thrown, invalid request URL.');
        }
        catch (RuntimeException $e) {}

        $response = $adapter->executeRequest('http://www.google.co.uk/');
        $this->assertType('string', $response);
        $this->assertTrue(strlen(trim($response)) > 0);
    }
}