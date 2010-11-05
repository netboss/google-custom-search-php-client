<?php

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
        $handle = @curl_init();
        if (!$handle)
        {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to create cURL session.');
            // @codeCoverageIgnoreEnd
        }

        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_URL, $url);

        $response = @curl_exec($handle);
        if (!$response)
        {
            throw new RuntimeException('API request failed. curl_exec() returned FALSE.');
        }

        $responseStatusCode = @curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($responseStatusCode != 200)
        {
            throw new RuntimeException(sprintf('API request failed. Status code "%s", expected "200".', $responseStatusCode));
        }

        return $response;
    }
}