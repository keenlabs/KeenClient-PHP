<?php

namespace KeenIO\Tests\Client;

use KeenIO\Client\KeenIOClient;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

class KeenIOClientTest extends \PHPUnit_Framework_TestCase
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
        $this->assertInstanceOf(GuzzleClient::class, $client);
        $this->assertInstanceOf(KeenIOClient::class, $client);

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
        $this->assertInstanceOf(GuzzleClient::class, $client);
        $this->assertInstanceOf(KeenIOClient::class, $client);

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
        $client = $this->getClient();
        $client->setProjectId('testProjectId');

        $this->assertEquals('testProjectId', $client->getConfig('projectId'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testReadKeySetter()
    {
        $client = $this->getClient();
        $client->setReadKey('testReadKey');

        $this->assertEquals('testReadKey', $client->getConfig('readKey'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testMasterKeySetter()
    {
        $client = $this->getClient();
        $client->setMasterKey('testMasterKey');

        $this->assertEquals('testMasterKey', $client->getConfig('masterKey'));
    }

    /**
     * Tests the client setter method and that the value returned is correct
     */
    public function testVersionSetter()
    {
        $client = $this->getClient();
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
        $queue = new MockHandler([
            new Response(200, [], '{response: true}')
        ]);
        $handler = HandlerStack::create($queue);
        $client = $this->getClient($handler);

        $command = $client->getCommand($method, $params);
        $client->execute($command);
        $request = $queue->getLastRequest();

        //Resource Url
        $url = parse_url($request->getUri());
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
     * @expectedException   GuzzleHttp\Command\Exception\CommandClientException
     */
    public function testServiceCommandsReturnExceptionOnInvalidAuth($method, $params)
    {
        $client = $this->getClient(HandlerStack::create(new MockHandler([
            new Response(401)
        ])));

        $command = $client->getCommand($method, $params);
        $client->execute($command);
    }

    /**
     * @dataProvider        providerServiceCommands
     * @expectedException   GuzzleHttp\Command\Exception\CommandClientException
     */
    public function testServiceCommandsReturnExceptionOnInvalidProjectId($method, $params)
    {
        $client = $this->getClient(HandlerStack::create(new MockHandler([
            new Response(404)
        ])));

        $command = $client->getCommand($method, $params);
        $client->execute($command);
    }

    /**
     * Uses mock response to test addEvent service method.  Also checks that event data
     * is properly json_encoded in the request body.
     */
    public function testSendEventMethod()
    {
        $queue = new MockHandler([
            new Response(200, [], '{"created": true}')
        ]);
        $client = $this->getClient(HandlerStack::create($queue));
        $event = array('foo' => 'bar', 'baz' => 1);

        $response = $client->addEvent('test', $event);
        $request = $queue->getLastRequest();

        //Resource Url
        $url = parse_url($request->getUri());

        $expectedResponse = array('created' => true);

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        //Checks that the response is good - based off mock response
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), json_encode($response));

        //Checks that the event is properly encoded in the request body
        $this->assertJsonStringEqualsJsonString(json_encode($event), (string) $request->getBody());
    }

    /**
     * @dataProvider      providerInvalidEvents
     * @expectedException GuzzleHttp\Command\Exception\CommandException
     */
    public function testSendEventReturnsExceptionOnBadDataType($event)
    {
        $client = $this->getClient(HandlerStack::create(new MockHandler([
            new Response(200, [], '{"created":true}')
        ])));
        $response = $client->addEvent('test', $event);
    }

    /**
     * Uses mock response to test addEvents service method.  Also checks that event data
     * is properly json_encoded in the request body.
     */
    public function testSendEventsMethod()
    {
        $events = array('test' => array(array('foo' => 'bar'), array('bar' => 'baz')));
        $queue = new MockHandler([
            new Response(200, [], '{"test":[{"success":true}, {"success":true}]}')
        ]);

        $client = $this->getClient(HandlerStack::create($queue));
        $response = $client->addEvents($events);

        //Resource Url
        $request = $queue->getLastRequest();
        $url = parse_url($request->getUri());

        $expectedResponse = array('test' => array(array('success' => true), array('success'=>true)));

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        //Checks that the response is good - based off mock response
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), json_encode($response));

        //Checks that the event is properly encoded in the request body
        $this->assertJsonStringEqualsJsonString(json_encode($events), (string) $request->getBody());
    }

    /**
     * @dataProvider                providerInvalidEvents
     * @expectedException           \Exception
     */
    public function testSendEventsReturnsExceptionOnBadDataType($events)
    {
        $client = $this->getClient();

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

    protected function getClient($handler = null)
    {
        return \KeenIO\Client\KeenIOClient::factory(array(
            'projectId' => 'testProjectId',
            'masterKey' => $_SERVER['MASTER_KEY'],
            'writeKey'  => $_SERVER['WRITE_KEY'],
            'readKey'   => $_SERVER['READ_KEY'],
            'version'   => $_SERVER['API_VERSION']
        ), $handler);
    }

    protected function setMockResponse($client, $file)
    {
        $file = __DIR__ . '../../mock/' . $file;
        $client->getEmitter()->attach(new Mock([$file]));
    }
}
