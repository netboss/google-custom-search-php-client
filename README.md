Google Custom Search API PHP Client
===================================

This client library enables you to interact with the Google Custom Search API in PHP allowing you to retrieve and display search results from your Google Custom Search programmatically.

Requirements
------------

This client library is only supported on PHP 5.2.4 and up.

The Google Custom Search API requires an API key, which you can get from the [Google APIs console][1].

You will also need a [Google Custom Search][2] ID or specification URL to search.

Installation
------------

Simply download the client library and add the `src` folder to your project.

Usage
-----

    require_once('src/Google/CustomSearch.php);

    $search = new Google_CustomSearch('search terms');
    $search->setApiKey('API_KEY');
    $search->setCustomSearchEngineId('017576662512468239146:omuauf_lfve');

    $response = $search->getResponse();

Testing
-------

To run the tests, make sure you have PHPUnit installed, and run,

    phpunit tests/

[1]: https://code.google.com/apis/console/?api=customsearch
[2]: http://www.google.com/cse/