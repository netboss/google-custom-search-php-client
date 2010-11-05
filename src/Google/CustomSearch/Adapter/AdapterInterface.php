<?php

/**
 * Google_CustomSearch_AdapterInterface defines the
 * interface of API request adapters.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
interface Google_CustomSearch_AdapterInterface
{
    /**
     * Executes the API request.
     *
     * @param string $url
     * @return string
     */
    public function executeRequest($url);
}