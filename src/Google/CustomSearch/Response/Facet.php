<?php

require_once(dirname(__FILE__).'/Data/DataAbstract.php');

/**
 * Google_CustomSearch_Response_Facet parses and defines a "facet" in a "context" in the API response
 * 
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Google_CustomSearch_Response_Facet extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var string
     */
    protected $anchor;

    /**
     * @var string
     */
    protected $label;

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
            'anchor',
            'label'
        ));
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets the displayable name of the item, which you
     * should use when displaying the item to a human.
     *
     * @return integer
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * Gets the label of the given facet item, which you
     * can use to refine your search.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}