<?php

require_once(dirname(__FILE__).'/../../src/Google/CustomSearch.php');

class Google_CustomSearchTest extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------
    // Properties
    // ------------------------------------------------------

    /**
     * @var Google_CustomSearch
     */
    protected $searchStub;

    // ------------------------------------------------------
    // Setup/Tear down
    // ------------------------------------------------------

    protected function setUp()
    {
        $this->searchStub = $this->getMock('Google_CustomSearch', array('executeApiRequest'));

        $this->searchStub->expects($this->any())
                            ->method('executeApiRequest')
                            ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/../fixtures/customsearch.json')));
    }

    // ------------------------------------------------------
    // Tests
    // ------------------------------------------------------

    public function testConstruct()
    {
        $search = new Google_CustomSearch();
        $this->assertNull($search->getQuery());

        $search = new Google_CustomSearch('Google');
        $this->assertEquals('Google', $search->getQuery());
    }

    public function dataSettingApiKey()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, ''),
            array(false, null),
            array(false, 'apikey')
        );
    }

    /**
     * @dataProvider dataSettingApiKey
     */
    public function testSettingApiKey($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setApiKey($data));
        $this->assertEquals($data, $this->searchStub->getApiKey());
    }

    public function dataSettingCustomSearchEngineId()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, ''),
            array(false, null),
            array(false, '017576662512468239146:omuauf_lfve')
        );
    }

    /**
     * @dataProvider dataSettingCustomSearchEngineId
     */
    public function testSettingCustomSearchEngineId($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setCustomSearchEngineId($data));
        $this->assertEquals($data, $this->searchStub->getCustomSearchEngineId());
    }

    public function dataSettingCustomSearchEngineSpecUrl()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, ''),
            array(true, 'en'),
            array(true, 'www.google.co.uk'),
            array(true, 'http://google/'),
            array(true, 'ssh://google.co.uk/'),
            array(false, null),
            array(false, 'http://google.co.uk/'),
            array(false, 'http://www.google.co.uk/'),
            array(false, 'https://google.co.uk/')
        );
    }

    /**
     * @dataProvider dataSettingCustomSearchEngineSpecUrl
     */
    public function testSettingCustomSearchEngineSpecUrl($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setCustomSearchEngineSpecUrl($data));
        $this->assertEquals($data, $this->searchStub->getCustomSearchEngineSpecUrl());
    }

    public function dataSettingLanguageRestriction()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, ''),
            array(true, 'en'),
            array(true, 'lang_zz'),
            array(false, null),
            array(false, 'lang_en'),
            array(false, 'lang_zh-CN')
        );
    }

    /**
     * @dataProvider dataSettingLanguageRestriction
     */
    public function testSettingLanguageRestriction($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setLanguageRestriction($data));
        $this->assertEquals($data, $this->searchStub->getLanguageRestriction());
    }

    public function dataSettingQuery()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, null),
            array(true, ''),
            array(false, 'bar')
        );
    }

    /**
     * @dataProvider dataSettingQuery
     */
    public function testSettingQuery($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setQuery($data));
        $this->assertEquals($data, $this->searchStub->getQuery());
    }

    public function dataSettingNumberOfResults()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, ''),
            array(true, 'bar'),
            array(true, 0),
            array(true, 10.1),
            array(true, 11),
            array(false, null),
            array(false, 1),
            array(false, 5),
            array(false, 10),
        );
    }

    /**
     * @dataProvider dataSettingNumberOfResults
     */
    public function testSettingNumberOfResults($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setNumberOfResults($data));
        $this->assertEquals($data, $this->searchStub->getNumberOfResults());
    }

    public function dataSettingSafeMode()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, false),
            array(true, ''),
            array(true, 'bar'),
            array(false, null),
            array(false, Google_CustomSearch::SAFE_MODE_ACTIVE),
            array(false, Google_CustomSearch::SAFE_MODE_MODERATE),
            array(false, Google_CustomSearch::SAFE_MODE_OFF)
        );
    }

    /**
     * @dataProvider dataSettingSafeMode
     */
    public function testSettingSafeMode($expectError, $data)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setSafeMode($data));
        $this->assertEquals($data, $this->searchStub->getSafeMode());
    }

    public function dataSettingStartIndex()
    {
        return array(
            array(true, array('foo')),
            array(true, true),
            array(true, false),
            array(true, ''),
            array(true, 'bar'),
            array(true, -1),
            array(true, 0),
            array(true, 1.1),
            array(true, 92),
            array(true, 101),
            array(false, null),
            array(false, 5),
            array(false, 91),
        );
    }

    /**
     * @dataProvider dataSettingStartIndex
     */
    public function testSettingStartIndex($expectError, $data, $expectedData = null)
    {
        $this->setExpectedException($expectError ? 'InvalidArgumentException' : null);
        $this->assertType('Google_CustomSearch', $this->searchStub->setStartIndex($data));
        $this->assertEquals($expectedData ? $expectedData : $data, $this->searchStub->getStartIndex());
    }

    public function dataGetApiRequestUrl()
    {
        return array(
            array(
                '?key=key&q=query',
                'query', 'key'
            ),
            array(
                '?key=key&cx=cseId&cref=http%3A%2F%2Fwww.google.co.uk%2F&lr=lang_en&num=8&q=query&safe=active&start=1',
                'query', 'key', 'cseId', 'http://www.google.co.uk/', 'lang_en', 8, 'active', 1
            ),
            array(
                '?key=key&cref=http%3A%2F%2Fwww.google.co.uk%2F&num=8&q=query&safe=active',
                'query', 'key', null, 'http://www.google.co.uk/', null, 8, 'active'
            )
        );
    }

    /**
     * @dataProvider dataGetApiRequestUrl
     */
    public function testGetApiRequestUrl($expectedResult, $query, $apiKey, $cseId = null, $cseSpecUrl = null, $lang = null, $numResults = null, $safeMode = null, $startIndex = null)
    {
        $this->searchStub->setApiKey($apiKey);
        $this->searchStub->setCustomSearchEngineId($cseId);
        $this->searchStub->setCustomSearchEngineSpecUrl($cseSpecUrl);
        $this->searchStub->setLanguageRestriction($lang);
        $this->searchStub->setNumberOfResults($numResults);
        $this->searchStub->setQuery($query);
        $this->searchStub->setSafeMode($safeMode);
        $this->searchStub->setStartIndex($startIndex);

        $this->assertEquals(
            Google_CustomSearch::API_URL . $expectedResult,
            $this->searchStub->getApiRequestUrl()
        );
    }

    public function testGetResponse()
    {
        try
        {
            $this->searchStub->getResponse();
            $this->fail('Excepted exception "LogicException" not thrown, API key missing.');
        }
        catch(LogicException $e)
        {
            $this->assertTrue(true);
        }

        $this->searchStub->setApiKey('key');

        try
        {
            $this->searchStub->getResponse();
            $this->fail('Excepted exception "LogicException" not thrown, CSE ID or spec URL missing.');
        }
        catch(LogicException $e)
        {
            $this->assertTrue(true);
        }

        $this->searchStub->setCustomSearchEngineId('017576662512468239146:omuauf_lfve');
        
        try
        {
            $this->searchStub->getResponse();
            $this->fail('Excepted exception "LogicException" not thrown, query missing.');
        }
        catch(LogicException $e)
        {
            $this->assertTrue(true);
        }

        $this->searchStub->setQuery('lectures');

        $response = $this->searchStub->getResponse();

        $response2 = $this->searchStub->getResponse();
        $this->assertTrue($response === $response2);
    }
}