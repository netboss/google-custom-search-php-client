<?php

require_once(dirname(__FILE__).'/ErrorException.php');
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
        if (!is_string($apiResponse))
        {
            throw new InvalidArgumentException('Invalid response format. Expected non-empty string.');
        }

        $response = @json_decode($apiResponse);
        if (!($response instanceof stdClass))
        {
            throw new Google_CustomSearch_ErrorException(
                'The response data could not be JSON decoded. Invalid format.',
                Google_CustomSearch_ErrorException::RESPONSE_JSON_INVALID
            );
        }

        if (isset($response->error))
        {
            throw new Google_CustomSearch_ErrorException(
                sprintf(
                    'API responded with error, code "%s", and message "%s".',
                    isset($response->error->code) ? $response->error->code : null,
                    isset($response->error->message) ? $response->error->message : null
                ),
                Google_CustomSearch_ErrorException::RESPONSE_API_ERROR
            );
        }

        if (!isset($response->kind) || $response->kind != self::KIND)
        {
            throw new Google_CustomSearch_ErrorException(
                sprintf('Invalid response kind. Expected "%s".', self::KIND),
                Google_CustomSearch_ErrorException::RESPONSE_KIND_INVALID
            );
        }

        if (isset($response->queries))
        {
            if (!($response->queries instanceof stdClass))
            {
                throw new Google_CustomSearch_ErrorException(
                    'Invalid response queries. Invalid format.',
                    Google_CustomSearch_ErrorException::RESPONSE_QUERIES_INVALID
                );
            }
            
            $this->parseQueries($response->queries);
        }

        if (isset($response->promotions))
        {
            if (!is_array($response->promotions))
            {
                throw new Google_CustomSearch_ErrorException(
                    'Invalid response promotions. Invalid format.',
                    Google_CustomSearch_ErrorException::RESPONSE_PROMOTIONS_INVALID
                );
            }

            $this->parsePromotions($response->promotions);
        }

        if (isset($response->context))
        {
            if (!($response->context instanceof stdClass))
            {
                throw new Google_CustomSearch_ErrorException(
                    'Invalid response context. Invalid format.',
                    Google_CustomSearch_ErrorException::RESPONSE_CONTEXT_INVALID
                );
            }
            
            $this->context = new Google_CustomSearch_Response_Context($response->context);
        }

        if (isset($response->items))
        {
            if (!is_array($response->items))
            {
                throw new Google_CustomSearch_ErrorException(
                    'Invalid response items/results. Invalid format.',
                    Google_CustomSearch_ErrorException::RESPONSE_ITEMS_INVALID
                );
            }
            
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
        $queriesData = array(
            array('query' => 'request', 'errorCode' => Google_CustomSearch_ErrorException::QUERY_REQUEST_INVALID),
            array('query' => 'nextPage', 'errorCode' => Google_CustomSearch_ErrorException::QUERY_NEXTPAGE_INVALID),
            array('query' => 'previousPage', 'errorCode' => Google_CustomSearch_ErrorException::QUERY_PREVIOUSPAGE_INVALID)
        );

        foreach($queriesData as $queryData)
        {
            if (isset($queries->{$queryData['query']}))
            {
                if (!is_array($queries->{$queryData['query']}) ||
                    !isset($queries->{$queryData['query']}[0]) ||
                    !($queries->{$queryData['query']}[0] instanceof stdClass))
                {
                    throw new Google_CustomSearch_ErrorException(
                        sprintf('Invalid response query "%s". Invalid format.', $queryData['query']),
                        $queryData['errorCode']
                    );
                }

                $this->queries[$queryData['query']] = new Google_CustomSearch_Response_Query($queries->{$queryData['query']}[0]);
            }
        }
    }

    /**
     * Parses the "promotions" data from the response
     *
     * @param stdClass $promotions
     */
    protected function parsePromotions(array $promotions)
    {
        foreach($promotions as $key => $promotion)
        {
            if (!($promotion instanceof stdClass))
            {
                throw new Google_CustomSearch_ErrorException(
                    sprintf('Invalid response promotion at index "%s". Invalid format.', $key),
                    Google_CustomSearch_ErrorException::PROMOTION_INVALID
                );
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
        foreach($results as $key => $result)
        {
            if (!($result instanceof stdClass))
            {
                throw new Google_CustomSearch_ErrorException(
                    sprintf('Invalid response item at index "%s". Invalid format.', $key),
                    Google_CustomSearch_ErrorException::ITEM_INVALID
                );
            }

            $resultObject = new Google_CustomSearch_Response_Result($result);

            array_push($this->results, $resultObject);
        }
    }

    /**
     * Gets the array index for the current page in the pages array.
     *
     * Note: Returns FALSE when current page can not be found, please use ===.
     *
     * @return integer
     * @see getPages()
     */
    public function getCurrentPageIndex()
    {
        $requestQuery = $this->getQuery(self::QUERY_REQUEST);
        if (!$requestQuery)
        {
            return false;
        }
        
        $pages = $this->getPages();
        foreach($pages as $key => $page)
        {
            if ($page['startIndex'] == $requestQuery->getStartIndex())
            {
                return $key;
            }
        }

        return false;
    }

    /**
     * Gets the pagination data for this search response.
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
     * Determines if there is pagination data for this search response.
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