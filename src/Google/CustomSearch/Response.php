<?php

require_once(dirname(__FILE__).'/Response/Context.php');
require_once(dirname(__FILE__).'/Response/Result.php');
require_once(dirname(__FILE__).'/Response/Promotion.php');
require_once(dirname(__FILE__).'/Response/Query.php');

/**
 * Google_CustomSearch_Response parses and formats the raw API response
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Google_CustomSearch_Response
{
    // ------------------------------------------------------
    // Constants
    // ------------------------------------------------------
    
    const KIND = 'customsearch#search';

    const QUERY_REQUEST = 'request';
    const QUERY_NEXT_PAGE = 'nextPage';
    const QUERY_PREVIOUS_PAGE = 'previousPage';

    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var Google_CustomSearch_Response_Context
     */
    protected $context;

    /**
     * @var array
     */
    protected $results = array();

    /**
     * @var array
     */
    protected $pages;

    /**
     * @var array
     */
    protected $promotions = array();

    /**
     * @var array
     */
    protected $queries = array();

    // ------------------------------------------------------
    // Constructor
    // ------------------------------------------------------

    /**
     * Creates a new Google_CustomSearch_Response
     *
     * @param string $apiResponse
     */
    public function __construct($apiResponse)
    {
        $this->parse($apiResponse);
    }

    // ------------------------------------------------------
    // Methods
    // ------------------------------------------------------

    /**
     * Parses the raw API response for validity and formats it
     *
     * @param string $apiResponse
     */
    protected function parse($apiResponse)
    {
        if (!is_string($apiResponse) || strlen(trim($apiResponse)) < 1)
        {
            throw new InvalidArgumentException('Invalid response format. Expected non-empty string.');
        }

        $response = @json_decode($apiResponse);
        if (!($response instanceof stdClass))
        {
            throw new RuntimeException('The response data could not be JSON decoded, invalid format.');
        }

        if (!isset($response->kind) || $response->kind != self::KIND)
        {
            throw new RuntimeException(sprintf('Invalid or missing response kind, expected "%s".', self::KIND));
        }

        if (isset($response->queries) && $response->queries instanceof stdClass)
        {
            $this->parseQueries($response->queries);
        }

        if (isset($response->context) && $response->context instanceof stdClass)
        {
            $this->context = new Google_CustomSearch_Response_Context($response->context);
        }

        if (isset($response->promotions) && is_array($response->promotions))
        {
            $this->parsePromotions($response->promotions);
        }

        if (isset($response->items) && is_array($response->items))
        {
            $this->parseResults($response->items);
        }
    }

    /**
     * Parses the "queries" data from the response
     *
     * @param stdClass $queries
     */
    protected function parseQueries(stdClass $queries)
    {
        if (isset($queries->request) && is_array($queries->request) && isset($queries->request[0]) && $queries->request[0] instanceof stdClass)
        {
            $this->queries['request'] = new Google_CustomSearch_Response_Query($queries->request[0]);
        }

        if (isset($queries->nextPage) && is_array($queries->nextPage) && isset($queries->nextPage[0]) && $queries->nextPage[0] instanceof stdClass)
        {
            $this->queries['nextPage'] = new Google_CustomSearch_Response_Query($queries->nextPage[0]);
        }

        if (isset($queries->previousPage) && is_array($queries->previousPage) && isset($queries->previousPage[0]) && $queries->previousPage[0] instanceof stdClass)
        {
            $this->queries['previousPage'] = new Google_CustomSearch_Response_Query($queries->previousPage[0]);
        }
    }

    /**
     * Parses the "promotions" data from the response
     *
     * @param stdClass $promotions
     */
    protected function parsePromotions(array $promotions)
    {
        foreach($promotions as $promotion)
        {
            if (!($promotion instanceof stdClass))
            {
                throw new RuntimeException('Invalid promotion format.');
            }

            $promotionObject = new Google_CustomSearch_Response_Promotion($promotion);
            array_push($this->promotions, $promotionObject);
        }
    }

    /**
     * Parses the "results" data from the response
     *
     * @param stdClass $results
     */
    protected function parseResults(array $results)
    {
        foreach($results as $result)
        {
            if (!($result instanceof stdClass))
            {
                throw new RuntimeException('Invalid result format.');
            }

            $resultObject = new Google_CustomSearch_Response_Result($result);

            array_push($this->results, $resultObject);
        }
    }

    /**
     * Gets the array index for the current page in the pages array.
     *
     * Note: Returns -1 when current page can not be found.
     *
     * @return integer
     * @see getPages()
     */
    public function getCurrentPageIndex()
    {
        $requestQuery = $this->getQuery(self::QUERY_REQUEST);
        if (!$requestQuery)
        {
            return -1;
        }
        
        $pages = $this->getPages();
        foreach($pages as $key => $page)
        {
            if ($page['startIndex'] == $requestQuery->getStartIndex())
            {
                return $key;
            }
        }

        return -1;
    }

    /**
     * Gets the pagination data for this search.
     * 
     * @return array
     */
    public function getPages()
    {
        if (!is_null($this->pages))
        {
            return $this->pages;
        }

        $requestQuery = $this->getQuery(self::QUERY_REQUEST);
        if (!$requestQuery)
        {
            return $this->pages = array();
        }

        $startIndex = $requestQuery->getStartIndex();
        $count = $requestQuery->getCount();

        foreach($this->getQueries() as $query)
        {
            if ($query->getCount() > $count)
            {
                $count = $query->getCount();
            }
        }

        if ($startIndex < 1 || $count < 1 || count($this->getQueries()) == 1)
        {
            return $this->pages = array();
        }

        $pageStartIndex = $startIndex;
        while($pageStartIndex > 1)
        {
            $pageStartIndex -= $count;
        }

        $maxStartIndex = 101 - $count;
        if ($requestQuery->getCount() != $count || is_null($this->getQuery(self::QUERY_NEXT_PAGE)))
        {
            $maxStartIndex = $requestQuery->getStartIndex();
        }

        $page = 1;
        $pages = array();
        
        while($pageStartIndex <= $maxStartIndex)
        {
            array_push($pages, array(
                'label'      => (string) $page,
                'startIndex' => ($pageStartIndex >= 1 ? $pageStartIndex : 1)
            ));

            $page++;
            $pageStartIndex += $count;
        }

        return $this->pages = $pages;
    }

    /**
     * Determines if there is pagination data for this search.
     *
     * @return boolean
     */
    public function hasPages()
    {
        return count($this->getPages()) > 0;
    }

    // ------------------------------------------------------
    // Getters
    // ------------------------------------------------------

    /**
     * Gets metadata about the particular search engine
     * that was used for performing the search query.
     * 
     * @return Google_CustomSearch_Response_Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Gets a set of Facet objects you can use for refining a search.
     * 
     * @return array
     */
    public function getContextFacets()
    {
        if ($this->hasContext())
        {
            return $this->getContext()->getFacets();
        }

        return null;
    }

    /**
     * Gets the set of subscribed links results.
     *
     * Note: Present only if the custom search engine's configuration
     * files define any subscribed links for the given query.
     *
     * @return array
     * @link https://code.google.com/apis/customsearch/docs/special_results.html#sl
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * Gets the query metadata, keyed by role name
     * (e.g. request, nextPage, previousPage).
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Gets a specific query metadata by keyed role name.
     * 
     * @param string $query
     * @return Google_CustomSearch_Response_Query
     * @see QUERY_REQUEST, QUERY_NEXT_PAGE, QUERY_PREVIOUS_PAGE
     */
    public function getQuery($query = self::QUERY_REQUEST)
    {
        if ($this->hasQueries() && isset($this->queries[$query]))
        {
            return $this->queries[$query];
        }

        return null;
    }

    /**
     * Gets the set of custom search results.
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Determines if there is metadata about the particular search engine
     * that was used for performing the search query.
     *
     * @return boolean
     */
    public function hasContext()
    {
        return !is_null($this->getContext());
    }

    /**
     * Determines if there are a set of subscribed links results.
     *
     * Note: Present only if the custom search engine's configuration
     * files define any subscribed links for the given query.
     *
     * @return boolean
     * @link https://code.google.com/apis/customsearch/docs/special_results.html#sl
     */
    public function hasPromotions()
    {
        return count($this->getPromotions()) > 0;
    }

    /**
     * Determines if there are query metadata, keyed by role name
     * (e.g. request, nextPage, previousPage).
     *
     * @return boolean
     */
    public function hasQueries()
    {
        return count($this->getQueries()) > 0;
    }

    /**
     * Determines if there are search results.
     *
     * @return boolean
     */
    public function hasResults()
    {
        return count($this->getResults()) > 0;
    }
}