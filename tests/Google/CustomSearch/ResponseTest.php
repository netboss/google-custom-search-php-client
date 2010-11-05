<?php

require_once(dirname(__FILE__).'/../../../src/Google/CustomSearch/Response.php');

class Google_CustomSearch_ResponseTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Static methods
    // ------------------------------------------------------

    public static function getFixturesDir()
    {
        return dirname(__FILE__) . '/Fixtures/';
    }

    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function dataParseJsonIncorrectFormat()
    {
        return array(
            array(true),
            array(array()),
            array(null),
            array(1)
        );
    }

    /**
     * @dataProvider dataParseJsonIncorrectFormat
     */
    public function testParseJsonIncorrectFormat($data)
    {
        $this->setExpectedException('InvalidArgumentException');
        $response = new Google_CustomSearch_Response($data);
    }

    public function testParseJson()
    {
        try
        {
            $response = new Google_CustomSearch_Response('invalid');
            $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', Google_CustomSearch_ErrorException::RESPONSE_JSON_INVALID));
        }
        catch(Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::RESPONSE_JSON_INVALID, $e->getCode());
        }
    }

    public function dataParseError()
    {
        $fixtures_dir = self::getFixturesDir();
        
        return array(
            array($fixtures_dir . 'error_500.json'),
            array($fixtures_dir . 'error_412.json')
        );
    }

    /**
     * @dataProvider dataParseError
     */
    public function testParseError($fixture)
    {
        try
        {
            $response = new Google_CustomSearch_Response(file_get_contents($fixture));
            $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', Google_CustomSearch_ErrorException::RESPONSE_API_ERROR));
        }
        catch(Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::RESPONSE_API_ERROR, $e->getCode());
        }
    }

    public function dataParseKind()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'kind_missing.json'),
            array($fixtures_dir . 'kind_invalid.json')
        );
    }

    /**
     * @dataProvider dataParseKind
     */
    public function testParseKind($fixture)
    {
        try
        {
            $response = new Google_CustomSearch_Response(file_get_contents($fixture));
            $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', Google_CustomSearch_ErrorException::RESPONSE_KIND_INVALID));
        }
        catch(Google_CustomSearch_ErrorException $e)
        {
            $this->assertEquals(Google_CustomSearch_ErrorException::RESPONSE_KIND_INVALID, $e->getCode());
        }
    }

    public function dataParseQueries()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'queries_missing.json'),
            array($fixtures_dir . 'queries_invalid.json', Google_CustomSearch_ErrorException::RESPONSE_QUERIES_INVALID),
            array($fixtures_dir . 'queries-request_missing.json'),
            array($fixtures_dir . 'queries-request_invalid_1.json', Google_CustomSearch_ErrorException::QUERY_REQUEST_INVALID),
            array($fixtures_dir . 'queries-request_invalid_2.json', Google_CustomSearch_ErrorException::QUERY_REQUEST_INVALID),
            array($fixtures_dir . 'queries-request_invalid_3.json', Google_CustomSearch_ErrorException::QUERY_REQUEST_INVALID),
            array($fixtures_dir . 'queries-nextPage_invalid.json', Google_CustomSearch_ErrorException::QUERY_NEXTPAGE_INVALID),
            array($fixtures_dir . 'queries-previousPage_invalid.json', Google_CustomSearch_ErrorException::QUERY_PREVIOUSPAGE_INVALID),
            array($fixtures_dir . 'queries-request_valid.json', null, 1),
            array($fixtures_dir . 'queries_valid.json', null, 3)
        );
    }

    /**
     * @dataProvider dataParseQueries
     */
    public function testParseQueries($fixture, $expectedErrorCode = null, $numberOfQueries = 0)
    {
        if (!is_null($expectedErrorCode))
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixture));
                $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', $expectedErrorCode));
            }
            catch(Google_CustomSearch_ErrorException $e)
            {
                $this->assertEquals($expectedErrorCode, $e->getCode());
                return true;
            }
        }
        else
        {
            $response = new Google_CustomSearch_Response(file_get_contents($fixture));
        }

        $this->assertEquals($numberOfQueries > 0, $response->hasQueries());
        $this->assertEquals($numberOfQueries, count($response->getQueries()));

        if ($numberOfQueries > 0)
        {
            $this->assertType('Google_CustomSearch_Response_Query', $response->getQuery(Google_CustomSearch_Response::QUERY_REQUEST));
        }
        else
        {
            $this->assertNull($response->getQuery(Google_CustomSearch_Response::QUERY_REQUEST));
        }
    }

    public function dataParseContext()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'context_missing.json'),
            array($fixtures_dir . 'context_invalid.json', Google_CustomSearch_ErrorException::RESPONSE_CONTEXT_INVALID),
            array($fixtures_dir . 'context_valid.json', null, true)
        );
    }

    /**
     * @dataProvider dataParseContext
     */
    public function testParseContext($fixture, $expectedErrorCode = null, $hasContext = false)
    {
        if (!is_null($expectedErrorCode))
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixture));
                $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', $expectedErrorCode));
            }
            catch(Google_CustomSearch_ErrorException $e)
            {
                $this->assertEquals($expectedErrorCode, $e->getCode());
                return true;
            }
        }
        else
        {
            $response = new Google_CustomSearch_Response(file_get_contents($fixture));
        }

        $this->assertEquals($hasContext, $response->hasContext());
        if ($hasContext)
        {
            $this->assertType('Google_CustomSearch_Response_Context', $response->getContext());
            $this->assertType('array', $response->getContextFacets());
            $this->assertEquals(0, count($response->getContextFacets()));
        }
        else
        {
            $this->assertNull($response->getContext());
            $this->assertNull($response->getContextFacets());
        }
    }

    public function dataParsePromotions()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'promotions_missing.json'),
            array($fixtures_dir . 'promotions_invalid_1.json', Google_CustomSearch_ErrorException::RESPONSE_PROMOTIONS_INVALID),
            array($fixtures_dir . 'promotions_invalid_2.json', Google_CustomSearch_ErrorException::PROMOTION_INVALID),
            array($fixtures_dir . 'promotions_valid.json', null, 2)
        );
    }

    /**
     * @dataProvider dataParsePromotions
     */
    public function testParsePromotions($fixture, $expectedErrorCode = null, $numberOfPromotions = 0)
    {
        if (!is_null($expectedErrorCode))
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixture));
                $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', $expectedErrorCode));
            }
            catch(Google_CustomSearch_ErrorException $e)
            {
                $this->assertEquals($expectedErrorCode, $e->getCode());
                return true;
            }
        }
        else
        {
            $response = new Google_CustomSearch_Response(file_get_contents($fixture));
        }

        $this->assertEquals($numberOfPromotions > 0, $response->hasPromotions());
        $this->assertEquals($numberOfPromotions, count($response->getPromotions()));
    }

    public function dataParseResults()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'items_missing.json'),
            array($fixtures_dir . 'items_invalid_1.json', Google_CustomSearch_ErrorException::RESPONSE_ITEMS_INVALID),
            array($fixtures_dir . 'items_invalid_2.json', Google_CustomSearch_ErrorException::ITEM_INVALID),
            array($fixtures_dir . 'items_valid.json', null, 2)
        );
    }

    /**
     * @dataProvider dataParseResults
     */
    public function testParseResults($fixture, $expectedErrorCode = null, $numberOfResults = 0)
    {
        if (!is_null($expectedErrorCode))
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixture));
                $this->fail(sprintf('Expected exception "Google_CustomSearch_ErrorException" with code "%s" not thrown.', $expectedErrorCode));
            }
            catch(Google_CustomSearch_ErrorException $e)
            {
                $this->assertEquals($expectedErrorCode, $e->getCode());
                return true;
            }
        }
        else
        {
            $response = new Google_CustomSearch_Response(file_get_contents($fixture));
        }

        $this->assertEquals($numberOfResults > 0, $response->hasResults());
        $this->assertEquals($numberOfResults, count($response->getResults()));
    }

    public function dataGetPages()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'queries_missing.json'),
            array($fixtures_dir . 'pages_invalid_1.json'),
            array(
                $fixtures_dir . 'pages_valid.json',
                array(
                    array('label' => '1', 'startIndex' => 1),
                    array('label' => '2', 'startIndex' => 2),
                    array('label' => '3', 'startIndex' => 12),
                ),
                2
            ),
        );
    }

    /**
     * @dataProvider dataGetPages
     */
    public function testGetPages($fixture, $pages = array(), $currentPageIndex = false)
    {
        $response = new Google_CustomSearch_Response(file_get_contents($fixture));

        $responsePages = $response->getPages();
        $this->assertEquals($pages != array(), $response->hasPages());
        $this->assertEquals($pages, $responsePages);
        
        $this->assertTrue($responsePages === $response->getPages());

        $this->assertTrue($currentPageIndex === $response->getCurrentPageIndex());
    }
}