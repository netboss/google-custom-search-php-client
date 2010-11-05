<?php

require_once(dirname(__FILE__).'/Context/Facet.php');
require_once(dirname(__FILE__).'/Data/DataAbstract.php');

/**
 * Google_CustomSearch_Response_Context parses and defines a "context" in the API response
 * 
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 */
class Google_CustomSearch_Response_Context extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var array
     */
    protected $facets = array();

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
            'title'
        ));

        $facets = self::getPropertyFromResponseData('facets', $resultData);
        if (is_array($facets))
        {
            foreach($facets as $facet)
            {
                if (is_array($facet) && isset($facet[0]) && $facet[0] instanceof stdClass)
                {
                    array_push($this->facets, new Google_CustomSearch_Response_Context_Facet($facet[0]));
                }
            }
        }
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets a set of Facet objects you can use for refining a search.
     * 
     * @return array
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * Gets the name of the search engine that was used for the query.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Determines if there any Facet objects.
     *
     * @return boolean
     */
    public function hasFacets()
    {
        return count($this->getFacets()) > 0;
    }
}