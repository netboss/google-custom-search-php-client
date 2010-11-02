<?php

require_once(dirname(__FILE__).'/Data/DataAbstract.php');
require_once(dirname(__FILE__).'/Promotion/Image.php');

/**
 * Google_CustomSearch_Response_Promotion parses and defines a "promotion" in the API response
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @see https://code.google.com/apis/customsearch/docs/special_results.html#sl
 */
class Google_CustomSearch_Response_Promotion extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var array
     */
    protected $bodyLines;

    /**
     * @var string
     */
    protected $displayLink;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $title;
    

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
            'displayLink',
            'link',
            'title'
        ));

        $bodyLines = self::getPropertyFromResponseData('bodyLines', $resultData);
        if (is_array($bodyLines) && isset($bodyLines[0]) && $bodyLines[0] instanceof stdClass)
        {
            $this->bodyLines = get_class_vars($bodyLines[0]);
        }
        
        $image = self::getPropertyFromResponseData('image', $resultData);
        if ($image instanceof stdClass)
        {
            $this->image = new Google_CustomSearch_Response_Promotion_Image($image);
        }
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets an array of block objects for this subscribed link.
     *
     * @return array
     * @see http://www.google.com/cse/docs/resultsxml.html
     */
    public function getBodyLines()
    {
        return $this->bodyLines;
    }

    /**
     * Gets an abridged version of this search's result URL, e.g. www.example.com.
     *
     * @return string
     */
    public function getDisplayLink()
    {
        return $this->displayLink;
    }

    /**
     * Gets the image associated with this subscribed link, if there is one.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Gets the URL of the subscribed link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Gets the title of the subscribed link.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}