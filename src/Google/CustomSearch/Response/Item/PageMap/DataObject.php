<?php

require_once(dirname(__FILE__).'/../../Data/DataAbstract.php');

/**
 * Google_CustomSearch_Response_Item_PageMap_DataObject parses and
 * defines a pagemap "data object" in the API response
 * 
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @see https://code.google.com/apis/customsearch/docs/snippets.html#pagemaps
 */
class Google_CustomSearch_Response_Item_PageMap_DataObject extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var stdClass
     */
    protected $pageMapDataObject;

    // ------------------------------------------------------
    // Constructor
    // ------------------------------------------------------
    
    // ------------------------------------------------------
    // Methods
    // ------------------------------------------------------

    /**
     * Mock method to satisfy abstract in parent class
     *
     * @param stdClass $resultData
     */
    protected function parse(stdClass $resultData)
    {
        $this->pageMapDataObject = $resultData;
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets a property from the PageMap DataObject
     *
     * @return mixed
     */
    public function getProperty($property)
    {
        return self::getPropertyFromResponseData($property, $this->pageMapDataObject);
    }
}