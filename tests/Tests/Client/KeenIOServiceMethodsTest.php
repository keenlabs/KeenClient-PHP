<?php

namespace KeenIO\Tests\Client;

use Guzzle\Tests\GuzzleTestCase;

class KeenIOServiceMethodsTest extends GuzzleTestCase
{
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
        $response = $client->addEvent(['event_collection' => 'test', 'data' => $event]);
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
     * Uses mock response to test addEvents service method.  Also checks that event data
     * is properly json_encoded in the request body.
     */
    public function testSendEventsMethod()
    {
        $events = array('test' => array(array('foo' => 'bar'), array('bar' => 'baz')));

        $client = $this->getServiceBuilder()->get('keen-io');

        $this->setMockResponse($client, 'add-events.mock');
        $response = $client->addEvents(array('data' => $events));
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
}
