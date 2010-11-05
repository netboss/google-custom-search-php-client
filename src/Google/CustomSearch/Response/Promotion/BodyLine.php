<?php

require_once(dirname(__FILE__).'/../Data/DataAbstract.php');

/**
 * Google_CustomSearch_Response_Promotion_BodyLine parses and defines a "promotion" "bodyLines" in the API response
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 */
class Google_CustomSearch_Response_Promotion_BodyLine extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

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
            'link',
            'title',
            'url'
        ));
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets the anchor text of the block object's link, if it has a link.
     *
     * @return integer
     */
    public function getLink()
    {
        return intval($this->link);
    }

    /**
     * Gets the block object's text, if it has text.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets URL of the block object's link, if it has one.
     *
     * @return integer
     */
    public function getUrl()
    {
        return intval($this->url);
    }
}