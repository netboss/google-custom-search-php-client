<?php

require_once(dirname(__FILE__).'/CustomSearch/Adapter/Curl.php');

/**
 * Google_CustomSearch performs a search using the Google Custom Search API
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * @link https://code.google.com/apis/customsearch/v1/overview.html
 * @link https://code.google.com/apis/customsearch/v1/reference.html
 */
class Google_CustomSearch
{
    // ------------------------------------------------------
    // Constants
    // ------------------------------------------------------

    const API_URL = 'https://www.googleapis.com/customsearch/v1';

    const API_ARG_API_KEY = 'key';
    const API_ARG_CUSTOM_SEARCH_ENGINE_ID = 'cx';
    const API_ARG_CUSTOM_SEARCH_ENGINE_SPEC_URL = 'cref';
    const API_ARG_LANGUAGE_RESTRICTION = 'lr';
    const API_ARG_NUMBER_OF_RESULTS = 'num';
    const API_ARG_PRETTYPRINT = 'prettyprint';
    const API_ARG_QUERY = 'q';
    const API_ARG_SAFE_MODE = 'safe';
    const API_ARG_START_INDEX = 'start';
    
    const SAFE_MODE_ACTIVE = 'active';
    const SAFE_MODE_MODERATE = 'moderate';
    const SAFE_MODE_OFF = 'off';

    const REGEX_LANGUAGE_RESTRICTION = "/^lang_(ar|bg|ca|cs|da|de|el|en|es|et|fi|fr|hr|hu|id|is|it|iw|ja|ko|lt|lv|nl|no|pl|pt|ro|ru|sk|sl|sr|sv|tr|zh\-CN|zh\-TW)$/";
    const REGEX_URL = "~^(http|https)://(([a-z0-9-]+\.)+[a-z]{2,6}|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(:[0-9]+)?(/?|/\S+)$~ix";

    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var array
     */
    private static $responseCache = array();

    /**
     * @var Google_CustomSearch_AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $customSearchEngineId;

    /**
     * @var string
     */
    protected $customSearchEngineSpecURL;

    /**
     * @var string
     */
    protected $languageRestriction;

    /**
     * @var integer
     */
    protected $numberOfResults;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $safeMode;

    /**
     * @var integer
     */
    protected $startIndex;

    // ------------------------------------------------------
    // Constructor
    // ------------------------------------------------------

    /**
     * Creates a new Google_CustomSearch
     *
     * @param string $query
     */
    public function __construct($query = null)
    {
        if (!is_null($query))
        {
            $this->setQuery($query);
        }

        $this->adapter = new Google_CustomSearch_Adapter_Curl();
    }

    // ------------------------------------------------------
    // Methods
    // ------------------------------------------------------

    /**
     * Generates and returns the request URL for this request.
     *
     * @return string
     */
    public function getApiRequestUrl()
    {
        $data = array(
            self::API_ARG_API_KEY                       => $this->getApiKey(),
            self::API_ARG_CUSTOM_SEARCH_ENGINE_ID       => $this->getCustomSearchEngineId(),
            self::API_ARG_CUSTOM_SEARCH_ENGINE_SPEC_URL => $this->getCustomSearchEngineSpecUrl(),
            self::API_ARG_LANGUAGE_RESTRICTION          => $this->getLanguageRestriction(),
            self::API_ARG_NUMBER_OF_RESULTS             => $this->getNumberOfResults(),
            self::API_ARG_QUERY                         => $this->getQuery(),
            self::API_ARG_SAFE_MODE                     => $this->getSafeMode(),
            self::API_ARG_START_INDEX                   => $this->getStartIndex()
        );

        $requestUrlArguments = array();

        foreach ($data as $key => $value)
        {
            if (!is_null($value))
            {
                array_push($requestUrlArguments, sprintf('%s=%s', $key, urlencode($value)));
            }
        }

        return self::API_URL . '?' . implode('&', $requestUrlArguments);
    }

    /**
     * Gets the API response for this request and parses
     * it into a Google_CustomSearch_Response.
     *
     * @return Google_CustomSearch_Response
     */
    public function getResponse()
    {
        $this->validateArguments();

        // @codeCoverageIgnoreStart
        if (!class_exists('Google_CustomSearch_Response', true))
        {
            require_once(dirname(__FILE__).'/CustomSearch/Response.php');
        }
        // @codeCoverageIgnoreEnd

        $cacheKey = md5($this->getApiRequestUrl());

        if (array_key_exists($cacheKey, self::$responseCache))
        {
            return self::$responseCache[$cacheKey];
        }

        $response = new Google_CustomSearch_Response($this->executeApiRequest());

        return self::$responseCache[$cacheKey] = $response;
    }

    /**
     * Validates that all the arguments are correct for this request.
     */
    protected function validateArguments()
    {
        if (is_null($this->getApiKey()))
        {
            throw new LogicException('Argument error. Please specify the API key argument.');
        }

        if (is_null($this->getCustomSearchEngineId()) && is_null($this->getCustomSearchEngineSpecUrl()))
        {
            throw new LogicException('Argument error. Please specify either the Custom Search Engine ID or specification URL.');
        }
        
        if (is_null($this->getQuery()))
        {
            throw new LogicException('Argument error. Please specifiy the query.');
        }
    }

    /**
     * Executes the API request and returns the raw response.
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected function executeApiRequest()
    {
        return $this->getAdapter()->executeRequest($this->getApiRequestUrl());
    }

    // ------------------------------------------------------
    // Property getters/setters
    // ------------------------------------------------------

    /**
     * Gets the API request adapter.
     *
     * @return Google_CustomSearch_AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Gets the API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Gets the Custom Search Engine ID to scope this search to.
     *
     * @return string
     */
    public function getCustomSearchEngineId()
    {
        return $this->customSearchEngineId;
    }

    /**
     * Gets the url of a linked Custom Search Engine specification.
     *
     * @return string
     */
    public function getCustomSearchEngineSpecUrl()
    {
        return $this->customSearchEngineSpecURL;
    }

    /**
     * Gets the language restriction that search to
     * documents must be written in.
     *
     * @return string
     * @link http://www.google.com/cse/docs/resultsxml.html#languageCollections
     */
    public function getLanguageRestriction()
    {
        return $this->languageRestriction;
    }
    
    /**
     * Gets the number of search results to return.
     *
     * @return integer
     */
    public function getNumberOfResults()
    {
        return $this->numberOfResults;
    }

    /**
     * Gets the search expression.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Gets the search safety level.
     *
     * @return string
     * @see SAFE_MODE_ACTIVE, SAFE_MODE_MODERATE, SAFE_MODE_OFF
     */
    public function getSafeMode()
    {
        return $this->safeMode;
    }

    /**
     * Gets the index of the first result to return.
     *
     * @return integer
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * Sets the API request adapter.
     *
     * @param Google_CustomSearch_AdapterInterface $adapter
     * @return Google_CustomSearch
     */
    public function setAdapter(Google_CustomSearch_AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Sets the API key.
     *
     * @param string $apiKey
     * @return Google_CustomSearch
     * @link https://code.google.com/apis/console
     */
    public function setApiKey($apiKey = null)
    {
        if (!is_null($apiKey) && !(is_string($apiKey) && strlen(trim($apiKey)) > 0))
        {
            throw new InvalidArgumentException('Invalid API key. Please provide a non-empty string.');
        }

        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Sets the Custom Search Engine ID to scope this
     * search to (e.g. 000455696194071821846:reviews).
     *
     * @param string $customSearchEngineId
     * @return Google_CustomSearch
     */
    public function setCustomSearchEngineId($customSearchEngineId = null)
    {
        if (!is_null($customSearchEngineId) && !(is_string($customSearchEngineId) && strlen(trim($customSearchEngineId)) > 0))
        {
            throw new InvalidArgumentException('Invalid Custom Search Engine ID. Please provide a non-empty string.');
        }

        $this->customSearchEngineId = $customSearchEngineId;
        return $this;
    }

    /**
     * Sets the url of a linked Custom Search Engine specification
     * (e.g. http://www.google.com/cse/samples/vegetarian.xml).
     *
     * @param string $customSearchEngineSpecURL
     * @return Google_CustomSearch
     */
    public function setCustomSearchEngineSpecUrl($customSearchEngineSpecURL = null)
    {
        if (!is_null($customSearchEngineSpecURL) && !(is_string($customSearchEngineSpecURL) && preg_match(self::REGEX_URL, $customSearchEngineSpecURL)))
        {
            throw new InvalidArgumentException('Invalid custom search engine spec URL. Please provide a valid full URL.');
        }

        $this->customSearchEngineSpecURL = $customSearchEngineSpecURL;
        return $this;
    }

    /**
     * Sets the language restriction that search to
     * documents must be written in.
     *
     * @param string $languageRestriction
     * @return Google_CustomSearch
     * @link http://www.google.com/cse/docs/resultsxml.html#languageCollections
     */
    public function setLanguageRestriction($languageRestriction = null)
    {
        if (!is_null($languageRestriction) && !(is_string($languageRestriction) && preg_match(self::REGEX_LANGUAGE_RESTRICTION, $languageRestriction)))
        {
            throw new InvalidArgumentException('Invalid language restriction. Please see http://www.google.com/cse/docs/resultsxml.html#languageCollections for a list of valid language codes.');
        }
        
        $this->languageRestriction = $languageRestriction;
        return $this;
    }

    /**
     * Sets the number of search results to return.
     *
     * @param integer $numberOfResults
     * @return Google_CustomSearch
     */
    public function setNumberOfResults($numberOfResults = null)
    {
        if (!is_null($numberOfResults) && !(is_numeric($numberOfResults) && $numberOfResults >= 1 && $numberOfResults <= 10 && (round($numberOfResults) == $numberOfResults)))
        {
            throw new InvalidArgumentException('Invalid number of results. Please provide an integer between 1 and 10.');
        }
        
        $this->numberOfResults = $numberOfResults;
        return $this;
    }

    /**
     * Sets the search expression.
     *
     * @param string $query
     * @return Google_CustomSearch
     */
    public function setQuery($query)
    {
        if (!(is_string($query) && strlen(trim($query)) > 0))
        {
            throw new InvalidArgumentException('Invalid query. Please provide a non-empty string.');
        }

        $this->query = $query;
        return $this;
    }

    /**
     * Sets the search safety level.
     *
     * @param string $safeMode
     * @return Google_CustomSearch
     * @see SAFE_MODE_ACTIVE, SAFE_MODE_MODERATE, SAFE_MODE_OFF
     */
    public function setSafeMode($safeMode = null)
    {
        if (!is_null($safeMode))
        {
            if (is_bool($safeMode))
            {
                throw new InvalidArgumentException('Invalid safe mode setting. Please provide either "active", "moderate" or "off".');
            }

            switch($safeMode)
            {
                case self::SAFE_MODE_ACTIVE:
                case self::SAFE_MODE_MODERATE:
                case self::SAFE_MODE_OFF:
                    break;
                default:
                    throw new InvalidArgumentException('Invalid safe mode setting. Please provide either "active", "moderate" or "off".');
                    break;
            }
        }

        $this->safeMode = $safeMode;
        return $this;
    }

    /**
     * Sets the index of the first result to return.
     *
     * @param integer $startIndex
     * @return Google_CustomSearch
     */
    public function setStartIndex($startIndex = null)
    {
        if (!is_null($startIndex) && !(is_numeric($startIndex) && $startIndex >= 1 && $startIndex <= (101 - ($this->getNumberOfResults() ? $this->getNumberOfResults() : 10)) && (round($startIndex) == $startIndex)))
        {
            throw new InvalidArgumentException('Invalid start index. Please provide an integer between 1 and 101-n, where n is the number of results.');
        }

        $this->startIndex = !is_null($startIndex) ? intval($startIndex) : $startIndex;
        return $this;
    }
}