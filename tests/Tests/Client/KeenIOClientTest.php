<?php

namespace KeenIO\Tests\Client;

use KeenIO\Client\KeenIOClient;
use Guzzle\Tests\GuzzleTestCase;

class KeenIOClientTest extends GuzzleTestCase
{
    /**
     * Check that a Keen IO Client is instantiated properly
     */
    public function testFactoryReturnsClient()
    {
        $config = array(
            'projectId' => 'testProjectId',
            'masterKey' => 'testMasterKey',
            'readKey'   => 'testReadKey',
            'writeKey'  => 'testWriteKey',
            'version'   => '3.0'
       );

        $client = KeenIOClient::factory($config);

        //Check that the Client is of the right type
        $this->assertInstanceOf('\Guzzle\Service\Client', $client);
        $this->assertInstanceOf('\KeenIO\Client\KeenIOClient', $client);

        //Check that the pass config options match the client's config
        $this->assertEquals($config['projectId'], $client->getConfig('projectId'));
        $this->assertEquals($config['masterKey'], $client->getConfig('masterKey'));
        $this->assertEquals($config['readKey'], $client->getConfig('readKey'));
        $this->assertEquals($config['writeKey'], $client->getConfig('writeKey'));
        $this->assertEquals($config['version'], $client->getConfig('version'));
    }

    /**
     * Check that a Keen IO Client is instantiated properly when version empty
     */
    public function testFactoryReturnsClientWhenVersionEmpty()
    {
        $defaultVersion = '3.0';

        $config = array(
            'projectId' => 'testProjectId',
            'masterKey' => 'testMasterKey',
            'readKey'   => 'testReadKey',
            'writeKey'  => 'testWriteKey',
            'version'   => ''
       );

        $client = KeenIOClient::factory($config);

        //Check that the Client is of the right type
        $this->assertInstanceOf('\Guzzle\Service\Client', $client);
        $this->assertInstanceOf('\KeenIO\Client\KeenIOClient', $client);

        //Check that the pass config options match the client's config
        $this->assertEquals($config['projectId'], $client->getConfig('projectId'));
        $this->assertEquals($config['masterKey'], $client->getConfig('masterKey'));
        $this->assertEquals($config['readKey'], $client->getConfig('readKey'));
        $this->assertEquals($config['writeKey'], $client->getConfig('writeKey'));
        $this->assertEquals($defaultVersion, $client->getConfig('version'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testProjectIdSetter()
    {
        $client = $this->getServiceBuilder()->get('keen-io');
        $client->setProjectId('testProjectId');

        $this->assertEquals('testProjectId', $client->getConfig('projectId'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testReadKeySetter()
    {
        $client = $this->getServiceBuilder()->get('keen-io');
        $client->setReadKey('testReadKey');

        $this->assertEquals('testReadKey', $client->getConfig('readKey'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testMasterKeySetter()
    {
        $client = $this->getServiceBuilder()->get('keen-io');
        $client->setMasterKey('testMasterKey');

        $this->assertEquals('testMasterKey', $client->getConfig('masterKey'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testVersionSetter()
    {
        $client = $this->getServiceBuilder()->get('keen-io');
        $client->setVersion('3.0');

        $this->assertEquals('3.0', $client->getConfig('version'));
    }

    /**
     * Tests the creation of a Scoped Key
     */
    public function testCreateLegacyScopedKey()
    {
        $client = KeenIOClient::factory(array(
            'masterKey' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'
        ));

        $filter = array('property_name' => 'id', 'operator' => 'eq', 'property_value' => '123');
        $filters = array($filter);
        $allowed_operations = array('read');

        $scopedKey = $client->createScopedKey($filters, $allowed_operations);

        $result = $client->decryptScopedKey($scopedKey);
        $expected = array('filters' => $filters, 'allowed_operations' => $allowed_operations);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the creation of a Scoped Key
     */
    public function testCreateScopedKey()
    {
        $client = KeenIOClient::factory(array(
            'masterKey' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB'
        ));

        $filter = array('property_name' => 'id', 'operator' => 'eq', 'property_value' => '123');
        $filters = array($filter);
        $allowed_operations = array('read');

        $scopedKey = $client->createScopedKey($filters, $allowed_operations);

        $result = $client->decryptScopedKey($scopedKey);
        $expected = array('filters' => $filters, 'allowed_operations' => $allowed_operations);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider providerServiceCommands
     */
    public function testServiceCommands($method, $params)
    {
        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'valid-response.mock');
        $result = $client->getCommand($method, $params)->getResult();

        $requests = $this->getMockedRequests();

        //Resource Url
        $url = parse_url($requests[0]->getUrl());
        parse_str($url['query'], $queryString);

        //Camel to underscore case
        $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $method));

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        //Make sure the url has the right method
        $this->assertContains($method, explode('/', $url['path']));

        //Check that the querystring has all the parameters
        $this->assertEquals($params, $queryString);
    }

    /**
     * Data for service calls
     */
    public function providerServiceCommands()
    {
        return array(
            array('count', array('event_collection' => 'test', 'timeframe' => 'this_week')),
            array('countUnique', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array('minimum', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array('maximum', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array('average', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array('sum', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array('selectUnique', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array('extraction', array('event_collection' => 'test', 'timeframe' => 'this_week', 'latest' => 10))
        );
    }

    /**
     * @dataProvider        providerServiceCommands
     * @expectedException   \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testServiceCommandsReturnExceptionOnInvalidAuth($method, $params)
    {
        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'invalid-auth.mock');
        $result = $client->getCommand($method, $params)->getResult();
    }

    /**
     * @dataProvider        providerServiceCommands
     * @expectedException   \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testServiceCommandsReturnExceptionOnInvalidProjectId($method, $params)
    {
        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'invalid-project-id.mock');
        $result = $client->getCommand($method, $params)->getResult();
    }

    /**
     * Uses mock response to test addEvent service method.  Also checks that event data
     * is properly json_encoded in the request body.
     */
    public function testSendEventMethod()
    {
        $event = array('foo' => 'bar', 'baz' => 1);

        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'add-event.mock');
        $response = $client->addEvent('test', $event);
        $requests = $this->getMockedRequests();

        //Resource Url
        $url = parse_url($requests[0]->getUrl());

        $expectedResponse = array('created' => true);

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        //Checks that the response is good - based off mock response
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), json_encode($response));

        //Checks that the event is properly encoded in the request body
        $this->assertJsonStringEqualsJsonString(json_encode($event), (string) $requests[0]->getBody());
    }

    /**
     * @dataProvider                providerInvalidEvents
     * @expectedException           Guzzle\Service\Exception\ValidationException
     */
    public function testSendEventReturnsExceptionOnBadDataType($event)
    {
        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'add-event.mock');
        $response = $client->addEvent('test', $event);
    }

    /**
     * Uses mock response to test addEvents service method.  Also checks that event data
     * is properly json_encoded in the request body.
     */
    public function testSendEventsMethod()
    {
        $events = array('test' => array(array('foo' => 'bar'), array('bar' => 'baz')));

        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'add-events.mock');
        $response = $client->addEvents($events);
        $requests = $this->getMockedRequests();

        //Resource Url
        $url = parse_url($requests[0]->getUrl());

        $expectedResponse = array('test' => array(array('success' => true), array('success'=>true)));

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        //Checks that the response is good - based off mock response
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), json_encode($response));

        //Checks that the event is properly encoded in the request body
        $this->assertJsonStringEqualsJsonString(json_encode($events), (string) $requests[0]->getBody());
    }

    /**
     * @dataProvider                providerInvalidEvents
     * @expectedException           \Exception
     */
    public function testSendEventsReturnsExceptionOnBadDataType($events)
    {
        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'add-events.mock');
        $response = $client->addEvents($events);
    }

    /**
     * Invalid data types for events
     */
    public function providerInvalidEvents()
    {
        $obj = new \stdClass();

        return array(
            array($obj),
            array('string'),
            array(12345),
        );
    }
}
