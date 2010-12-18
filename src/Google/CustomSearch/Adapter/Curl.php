<?php

require_once(dirname(__FILE__).'/../ErrorException.php');
require_once(dirname(__FILE__).'/AdapterInterface.php');

/**
 * Google_CustomSearch_Adapter_Curl excutes an API request using cURL.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Google_CustomSearch_Adapter_Curl implements Google_CustomSearch_AdapterInterface
{
    /**
     * Executes the API request using cURL.
     * 
     * @param string $url
     * @return string
     */
    public function executeRequest($url)
    {
        if (!function_exists('curl_init'))
        {
            // @codeCoverageIgnoreStart
            throw new Google_CustomSearch_ErrorException(
                'PHP cURL functions not found. Please make sure cURL is installed and enabled.',
                Google_CustomSearch_ErrorException::ADAPTER_CURL_MISSING
            );
            // @codeCoverageIgnoreEnd
        }

        $handle = @curl_init();
        if (!$handle)
        {
            // @codeCoverageIgnoreStart
            throw new Google_CustomSearch_ErrorException(
                'Unable to create cURL session.',
                Google_CustomSearch_ErrorException::ADAPTER_CURL_UNABLE_INIT_CURL
            );
            // @codeCoverageIgnoreEnd
        }

        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_URL, $url);

        $response = @curl_exec($handle);
        if (!$response)
        {
            throw new Google_CustomSearch_ErrorException(
                'API request failed. curl_exec() returned FALSE.',
                Google_CustomSearch_ErrorException::ADAPTER_CURL_EXECUTE_FAILED
            );
        }

        return $response;
    }
}