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
            'readKey' => 'testReadKey',
            'writeKey' => 'testWriteKey',
            'version' => '3.0'
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
            'readKey' => 'testReadKey',
            'writeKey' => 'testWriteKey',
            'version' => ''
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
     * Tests that the magic __call correctly merges in the event_collection parameter
     */
    public function testEventCollectionMerging()
    {
        $unmerged = array('collection', array('timeframe' => 'this_14_days'));
        $merged = array(array('event_collection' => 'collection', 'timeframe' => 'this_14_days'));

        $client = $this->getClient();

        $unmergedAfter = $this->invokeMethod($client, 'combineEventCollectionArgs', array($unmerged));
        $mergedAfter = $this->invokeMethod($client, 'combineEventCollectionArgs', array($merged));

        $this->assertEquals($unmergedAfter['event_collection'], 'collection');
        $this->assertEquals($unmergedAfter['timeframe'], 'this_14_days');
        $this->assertEquals($mergedAfter['event_collection'], 'collection');
        $this->assertEquals($mergedAfter['timeframe'], 'this_14_days');
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
     * Tests that the client is able to fallback to the masterkey when
     * a write key isn't given
     */
    public function testWriteKeyPicker()
    {
        $client = $this->getClient();
        $client->setWriteKey('bar');
        $client->setMasterKey('foo');

        $this->assertNotEquals(
            $client->getMasterKey(),
            $client->getKeyForWriting()
        );

        $client->setWriteKey('');

        $this->assertEquals(
            $client->getMasterKey(),
            $client->getKeyForWriting()
        );
    }

    /**
     * Tests that the client is able to fallback to the masterkey when
     * a read key isn't given
     */
    public function testReadKeyPicker()
    {
        $client = $this->getClient();
        $client->setReadKey('bar');
        $client->setMasterKey('foo');

        $this->assertNotEquals(
            $client->getMasterKey(),
            $client->getKeyForReading()
        );

        $client->setReadKey('');

        $this->assertEquals(
            $client->getMasterKey(),
            $client->getKeyForReading()
        );
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
     * Tests the decryption of a Scoped Key
     */
    public function testDecryptLegacyScopedKey()
    {
        $client = KeenIOClient::factory(array(
            'masterKey' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'
        ));

        $filter = array('property_name' => 'id', 'operator' => 'eq', 'property_value' => '123');
        $filters = array($filter);
        $allowed_operations = array('read');

        // @codingStandardsIgnoreLine - It is not possible to get this below 120 characters
        $result = $client->decryptScopedKey('696a2c05b060e6ed344f7e570c101e0909ff97c338dfe0f1e15c0e5c1ec0621dfcd5577f3ae58596558eed2a43c82d3a062fbf6455810f5c859695c766caddd283398e577b24db014fad896a6f0447a2aad9dad43cef5fa040e8f6d366085423804633ef3b21535b31d11eec24631f83c18e83703247f40136aeba779a840e80013e0969a8cf203295f47da1d70bfeb3');
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
     * Tests the decryption of a Scoped Key
     */
    public function testDecryptScopedKey()
    {
        $client = KeenIOClient::factory(array(
            'masterKey' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB'
        ));

        $filter = array('property_name' => 'id', 'operator' => 'eq', 'property_value' => '123');
        $filters = array($filter);
        $allowed_operations = array('read');

        // @codingStandardsIgnoreLine - It is not possible to get this below 120 characters
        $result = $client->decryptScopedKey('903441674b0d433ddab759bba82502ec469e00d25c373e35c4d685488bc7779a5abd7d90a03a4cb744ee6a82fa8935804348a5b2351f6527cd5fd6a0613cea5ec4e848f5093e41a53d570cf01066b1f3c3e9b03d4ce0929ff3e6a06e1850fb9e09b65415ac754bbefe9db4b1fcba7d71a9f5f9d9c05cbeffb2a33ef5f4bac131');
        $expected = array('filters' => $filters, 'allowed_operations' => $allowed_operations);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider providerServiceCommands
     */
    public function testServiceCommands($method, $params)
    {
        $queue = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"response": true}')
        ]);
        $handler = HandlerStack::create($queue);
        $client = $this->getClient($handler);

        $command = $client->getCommand($method, $params);
        $result = $client->execute($command);
        $request = $queue->getLastRequest();

        //Resource Url
        $url = parse_url($request->getUri());
        $body = json_decode($request->getBody()->getContents(), true);

        //Camel to underscore case
        $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $method));

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        $this->assertEquals($client->getKeyForReading(), $request->getHeader('Authorization')[0]);

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        //Make sure the url has the right method
        $this->assertContains($method, explode('/', $url['path']));

        //Check that the json body has all of the parameters
        $this->assertEquals(count($params), count($body));
        foreach ($params as $param => $value) {
            $this->assertEquals($value, $body[$param]);
        }

        // Make sure that the response is a PHP array, according to the documented return type
        $this->assertInternalType('array', $result);
    }

    /**
     * Data for service calls
     */
    public function providerServiceCommands()
    {
        return array(
            array('count', array('event_collection' => 'test', 'timeframe' => 'this_week')),
            array(
                'countUnique',
                array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')
            ),
            array(
                'minimum',
                array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')
            ),
            array(
                'maximum',
                array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')
            ),
            array(
                'average',
                array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')
            ),
            array('sum', array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')),
            array(
                'selectUnique',
                array('event_collection' => 'test', 'target_property' => 'foo', 'timeframe' => 'this_week')
            ),
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

        $this->assertEquals($client->getKeyForWriting(), $request->getHeader('Authorization')[0]);

        //Checks that the response is good - based off mock response
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), json_encode($response));

        //Checks that the event is properly encoded in the request body
        $this->assertJsonStringEqualsJsonString(json_encode($event), (string)$request->getBody());
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

        $expectedResponse = array('test' => array(array('success' => true), array('success' => true)));

        //Make sure the projectId is set properly in the url
        $this->assertContains($client->getProjectId(), explode('/', $url['path']));

        //Make sure the version is set properly in the url
        $this->assertContains($client->getVersion(), explode('/', $url['path']));

        $this->assertEquals($client->getKeyForWriting(), $request->getHeader('Authorization')[0]);

        //Checks that the response is good - based off mock response
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), json_encode($response));

        //Checks that the event is properly encoded in the request body
        $this->assertJsonStringEqualsJsonString(json_encode($events), (string)$request->getBody());
    }

    protected function getClient($handler = null)
    {
        return \KeenIO\Client\KeenIOClient::factory(array(
            'projectId' => $_SERVER['PROJECT_ID'],
            'masterKey' => $_SERVER['MASTER_KEY'],
            'writeKey' => $_SERVER['WRITE_KEY'],
            'readKey' => $_SERVER['READ_KEY'],
            'version' => $_SERVER['API_VERSION'],
            'handler' => $handler
        ));
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
