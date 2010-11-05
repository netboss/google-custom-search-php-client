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
        $fixtures_dir = self::getFixturesDir();

        $testCases = array(
            'invalid_json.json'
        );

        foreach($testCases as $testCase)
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixtures_dir . $testCase));
                $this->fail('Expected exception "RuntimeException" not thrown.');
            }
            catch(RuntimeException $e) {}
        }
    }

    public function testParseError()
    {
        $fixtures_dir = self::getFixturesDir();

        $testCases = array(
            'error_500.json',
            'error_412.json'
        );

        foreach($testCases as $testCase)
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixtures_dir . $testCase));
                $this->fail('Expected exception "RuntimeException" not thrown.');
            }
            catch(RuntimeException $e) {}
        }
    }

    public function testParseKind()
    {
        $fixtures_dir = self::getFixturesDir();

        $testCases = array(
            'kind_missing.json',
            'kind_invalid.json'
        );

        foreach($testCases as $testCase)
        {
            try
            {
                $response = new Google_CustomSearch_Response(file_get_contents($fixtures_dir . $testCase));
                $this->fail('Expected exception "RuntimeException" not thrown.');
            }
            catch(RuntimeException $e) {}
        }
    }

    public function dataParseQueries()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'queries_missing.json'),
            array($fixtures_dir . 'queries_invalid.json'),
            array($fixtures_dir . 'queries-request_missing.json'),
            array($fixtures_dir . 'queries-request_invalid_1.json'),
            array($fixtures_dir . 'queries-request_invalid_2.json'),
            array($fixtures_dir . 'queries-request_invalid_3.json'),
            array($fixtures_dir . 'queries-request_valid.json', 1),
            array($fixtures_dir . 'queries_valid.json', 3)
        );
    }

    /**
     * @dataProvider dataParseQueries
     */
    public function testParseQueries($fixture, $numberOfQueries = 0)
    {
        $response = new Google_CustomSearch_Response(file_get_contents($fixture));

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
            array($fixtures_dir . 'context_invalid.json'),
            array($fixtures_dir . 'context_valid.json', true)
        );
    }

    /**
     * @dataProvider dataParseContext
     */
    public function testParseContext($fixture, $hasContext = false)
    {
        $response = new Google_CustomSearch_Response(file_get_contents($fixture));

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
            array($fixtures_dir . 'promotions_invalid_1.json'),
            array($fixtures_dir . 'promotions_invalid_2.json', 0, true),
            array($fixtures_dir . 'promotions_valid.json', 2)
        );
    }

    /**
     * @dataProvider dataParsePromotions
     */
    public function testParsePromotions($fixture, $numberOfPromotions = 0, $expectError = false)
    {
        $this->setExpectedException($expectError ? 'RuntimeException' : null);

        $response = new Google_CustomSearch_Response(file_get_contents($fixture));

        $this->assertEquals($numberOfPromotions > 0, $response->hasPromotions());
        $this->assertEquals($numberOfPromotions, count($response->getPromotions()));
    }

    public function dataParseResults()
    {
        $fixtures_dir = self::getFixturesDir();

        return array(
            array($fixtures_dir . 'items_missing.json'),
            array($fixtures_dir . 'items_invalid_1.json'),
            array($fixtures_dir . 'items_invalid_2.json', 0, true),
            array($fixtures_dir . 'items_valid.json', 2)
        );
    }

    /**
     * @dataProvider dataParseResults
     */
    public function testParseResults($fixture, $numberOfResults = 0, $expectError = false)
    {
        $this->setExpectedException($expectError ? 'RuntimeException' : null);
        
        $response = new Google_CustomSearch_Response(file_get_contents($fixture));

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
    public function testGetPages($fixture, $pages = array(), $currentPageIndex = -1)
    {
        $response = new Google_CustomSearch_Response(file_get_contents($fixture));

        $responsePages = $response->getPages();
        $this->assertEquals($pages != array(), $response->hasPages());
        $this->assertEquals($pages, $responsePages);
        
        $this->assertTrue($responsePages === $response->getPages());

        $this->assertEquals($currentPageIndex, $response->getCurrentPageIndex());
    }
}