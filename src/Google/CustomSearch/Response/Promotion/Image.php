<?php

require_once(dirname(__FILE__).'/../Data/DataAbstract.php');

/**
 * Google_CustomSearch_Response_Promotion_Image parses and defines a "promotion" "image" in the API response
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 */
class Google_CustomSearch_Response_Promotion_Image extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var integer
     */
    protected $height;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var integer
     */
    protected $width;

    // ------------------------------------------------------
    // Methods
    // ------------------------------------------------------

    /**
     * Parses the raw result data for validity and then into formatted data
     *
     * @param stdClass $resultData
     */
    protected function parse(stdClass $resultData)
    {
        $this->parseStandardProperties($resultData, array(
            'height',
            'source',
            'width'
        ));
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets the image height in pixels.
     *
     * @return integer
     */
    public function getHeight()
    {
        return intval($this->height);
    }

    /**
     * Gets the URL of the image for this subscribed link.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Gets the image width in pixels.
     *
     * @return integer
     */
    public function getWidth()
    {
        return intval($this->width);
    }
}