<?php

require_once(dirname(__FILE__).'/Data/DataAbstract.php');

/**
 * Google_CustomSearch_Response_Query parses and defines a "query" in the API response
 * 
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 */
class Google_CustomSearch_Response_Query extends Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var integer
     */
    protected $count;

    /**
     * @var string
     */
    protected $cref;

    /**
     * @var string
     */
    protected $cx;

    /**
     * @var string
     */
    protected $inputEncoding;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $outputEncoding;

    /**
     * @var string
     */
    protected $safe;

    /**
     * @var string
     */
    protected $searchTerms;

    /**
     * @var integer
     */
    protected $startIndex;

    /**string
     * @var integer
     */
    protected $startPage;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var integer
     */
    protected $totalResults;

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
            'count',
            'cref',
            'cx',
            'inputEncoding',
            'language',
            'outputEncoding',
            'safe',
            'searchTerms',
            'startIndex',
            'startPage',
            'title',
            'totalResults'
        ));
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets the number of search results returned in this set.
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Gets the URL pointing to the definition of a linked
     * custom search engine, if specified in request.
     *
     * @return string
     */
    public function getCustomSearchEngineSpecUrl()
    {
        return $this->cref;
    }

    /**
     * Gets the identifier of a custom search engine created by
     * visiting http://www.google.com/cse, if specified in request.
     *
     * @return string
     */
    public function getCustomSearchEngineId()
    {
        return $this->cx;
    }

    /**
     * Gets the character encoding supported for search requests.
     *
     * @return string
     */
    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }

    /**
     * Gets the language of the search results.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Gets the character encoding of the search results.
     *
     * @return string
     */
    public function getOutputEncoding()
    {
        return $this->outputEncoding;
    }

    /**
     * Gets the safe search level used for filtering results.
     *
     * @return string
     * @see Google_CustomSearch::SAFE_MODE_ACTIVE,
     *      Google_CustomSearch::SAFE_MODE_MODERATE,
     *      Google_CustomSearch::SAFE_MODE_OFF
     */
    public function getSafeMode()
    {
        return $this->safe;
    }

    /**
     * Gets the search terms.
     *
     * @return string
     */
    public function getSearchTerms()
    {
        return $this->searchTerms;
    }

    /**
     * Gets the index of the current set of search results
     * into the total set of results, where the index of
     * the first result is 1.
     *
     * @return integer
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * Gets the page number of this set of results, where
     * the page length is set by the count property.
     *
     * @return integer
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * Gets the description of the query.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets the number of total search results.
     *
     * @return integer
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }
}