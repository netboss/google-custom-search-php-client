<?php

require_once(dirname(__FILE__).'/../ErrorException.php');
require_once(dirname(__FILE__).'/AdapterInterface.php');

/**
 * Google_CustomSearch_Adapter_FileGetContents excutes an API request using file_get_contents().
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Google_CustomSearch_Adapter_FileGetContents implements Google_CustomSearch_AdapterInterface
{
    /**
     * Executes the API request using file_get_contents().
     *
     * @param string $url
     * @return string
     */
    public function executeRequest($url)
    {
        $response = @file_get_contents($url);
        if (!$response)
        {
            throw new Google_CustomSearch_ErrorException(
                'API request failed. file_get_contents() returned FALSE.',
                Google_CustomSearch_ErrorException::ADAPTER_FILE_GET_CONTENTS_EXECUTE_FAILED
            );
        }

        return $response;
    }
}