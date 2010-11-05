<?php

require_once(dirname(__FILE__).'/../Data/DataAbstract.php');
require_once(dirname(__FILE__).'/PageMap/DataObject.php');

/**
 * Google_CustomSearch_Response_Result_PageMap parses and defines a "pagemap" in the API response
 * 
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 * @link https://code.google.com/apis/customsearch/docs/snippets.html#pagemaps
 */
class Google_CustomSearch_Response_Result_PageMap extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var array
     */
    protected $dataObjects = array();

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
        $pageMapDataObjects = get_object_vars($resultData);
        
        foreach($pageMapDataObjects as $key => $pageMapDataObject)
        {
            if (is_array($pageMapDataObject) && isset($pageMapDataObject[0]) && $pageMapDataObject[0] instanceof stdClass)
            {
                $this->dataObjects[$key] = new Google_CustomSearch_Response_Result_PageMap_DataObject($pageMapDataObject[0]);
            }
        }
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets the specific DataObject for the given key.
     * 
     * @param string $key
     * @return Google_CustomSearch_Response_Result_PageMap_DataObject
     */
    public function getDataObject($key)
    {
        $dataObjects = $this->getDataObjects();
        return isset($dataObjects[$key]) ? $dataObjects[$key] : null;
    }

    /**
     * Gets the DataObjects for the PageMap.
     *
     * @return array
     */
    public function getDataObjects()
    {
        return $this->dataObjects;
    }

    /**
     * Determines if there are DataObjects within this PageMap.
     *
     * @return array
     */
    public function hasDataObjects()
    {
        return count($this->getDataObjects()) > 0;
    }
}