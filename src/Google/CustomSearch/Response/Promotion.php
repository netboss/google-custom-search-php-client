<?php

require_once(dirname(__FILE__).'/Data/DataAbstract.php');
require_once(dirname(__FILE__).'/Promotion/BodyLine.php');
require_once(dirname(__FILE__).'/Promotion/Image.php');

/**
 * Google_CustomSearch_Response_Promotion parses and defines a "promotion" in the API response
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 * @link https://code.google.com/apis/customsearch/docs/special_results.html#sl
 */
class Google_CustomSearch_Response_Promotion extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var array
     */
    protected $bodyLines = array();

    /**
     * @var string
     */
    protected $displayLink;

    /**
     * @var Google_CustomSearch_Response_Promotion_Image
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
            foreach($bodyLines as $bodyLine)
            {
                if ($bodyLine instanceof stdClass)
                {
                    array_push($this->bodyLines, new Google_CustomSearch_Response_Promotion_BodyLine($bodyLine));
                }
            }
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
    public function getBodyLine()
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
     * @return Google_CustomSearch_Response_Promotion_Image
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

    /**
     * Determines if there are block objects for this subscribed link.
     *
     * @return boolean
     * @see http://www.google.com/cse/docs/resultsxml.html
     */
    public function hasBodyLine()
    {
        return count($this->getBodyLine()) > 0;
    }
}