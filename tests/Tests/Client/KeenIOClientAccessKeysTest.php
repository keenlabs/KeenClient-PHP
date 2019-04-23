<?php

namespace KeenIO\Tests\Client;

use KeenIO\Client\KeenIOClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class KeenIOClientAccessKeysTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Access Key Creation
     */
    public function testCreateAccessKey()
    {
        $newKey = 'AAAA';
        $keyName = 'Test_Key';
        $isKeyActive = true;
        $keyPermitted = ['writes', 'cached_queries'];
        $projectId = 'anyAIDIDID';

        $response = array(
            'key' => $newKey,
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted,
            'project_id'=> $projectId
        );

        $client = $this->getClient($response);
        $result = $client->createAccessKey([
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted
        ]);

        $this->assertEquals($newKey, $result['key']);
        $this->assertEquals($keyName, $result['name']);
        $this->assertEquals($isKeyActive, $result['is_active']);
        $this->assertEquals($keyPermitted, $result['permitted']);
        $this->assertEquals($projectId, $result['project_id']);
    }

    /**
     * Test List Access Keys
     */
    public function testListAccessKeys()
    {
        $newKey = 'AAAA';
        $keyName = 'Test_Key';
        $isKeyActive = true;
        $keyPermitted = ['writes', 'cached_queries'];
        $projectId = 'd2334wwer';

        $response = array(
            array(
                'key' => $newKey,
                'name' => $keyName,
                'is_active' => $isKeyActive,
                'permitted' => $keyPermitted,
                'project_id'=> $projectId
            )
        );

        $client = $this->getClient($response);
        $results = $client->listAccessKeys();
        $result = $results[0];

        $this->assertEquals($newKey, $result['key']);
        $this->assertEquals($keyName, $result['name']);
        $this->assertEquals($isKeyActive, $result['is_active']);
        $this->assertEquals($keyPermitted, $result['permitted']);
        $this->assertEquals($projectId, $result['project_id']);
    }

    /**
     * Test Get Access Key
     */
    public function testGetAccessKey()
    {
        $key = 'ddfdf';
        $keyName = 'Test_Key';
        $isKeyActive = true;
        $keyPermitted = ['writes', 'cached_queries'];
        $projectId = 'd2334wwer';

        $response = array(
            'key' => $key,
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted,
            'project_id'=> $projectId
        );

        $client = $this->getClient($response);
        $result = $client->getAccessKey([
            'key' => $key
        ]);

        $this->assertEquals($key, $result['key']);
        $this->assertEquals($keyName, $result['name']);
        $this->assertEquals($isKeyActive, $result['is_active']);
        $this->assertEquals($keyPermitted, $result['permitted']);
        $this->assertEquals($projectId, $result['project_id']);
    }

    /**
     * Test Update Access Key
     */
    public function testUpdateAccessKey()
    {
        $key = 'ddfdf';
        $keyName = 'newKey';
        $isKeyActive = true;
        $keyPermitted = ['writes'];
        $projectId = 'd2334wwer';

        $response = array(
            'key' => $key,
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted,
            'project_id'=> $projectId
        );

        $client = $this->getClient($response);
        $result = $client->updateAccessKey([
            'key' => $key,
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted
        ]);

        $this->assertEquals($key, $result['key']);
        $this->assertEquals($keyName, $result['name']);
        $this->assertEquals($isKeyActive, $result['is_active']);
        $this->assertEquals($keyPermitted, $result['permitted']);
        $this->assertEquals($projectId, $result['project_id']);
    }

    /**
     * Test Revoke Access Key
     */
    public function testRevokeAccessKey()
    {
        $key = 'ddfdf';
        $keyName = 'newKey';
        $isKeyActive = false;
        $keyPermitted = ['writes'];
        $projectId = 'd2334wwer';

        $response = array(
            'key' => $key,
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted,
            'project_id'=> $projectId
        );

        $client = $this->getClient($response);
        $result = $client->revokeAccessKey([
            'key' => $key
        ]);

        $this->assertEquals($key, $result['key']);
        $this->assertEquals($keyName, $result['name']);
        $this->assertEquals($isKeyActive, $result['is_active']);
        $this->assertEquals($keyPermitted, $result['permitted']);
        $this->assertEquals($projectId, $result['project_id']);
    }

    /**
     * Test Un-Revoke Access Key
     */
    public function testUnRevokeAccessKey()
    {
        $key = 'ddfdf';
        $keyName = 'newKey';
        $isKeyActive = true;
        $keyPermitted = ['writes'];
        $projectId = 'd2334wwer';

        $response = array(
            'key' => $key,
            'name' => $keyName,
            'is_active' => $isKeyActive,
            'permitted' => $keyPermitted,
            'project_id'=> $projectId
        );

        $client = $this->getClient($response);
        $result = $client->unRevokeAccessKey([
            'key' => $key
        ]);

        $this->assertEquals($key, $result['key']);
        $this->assertEquals($keyName, $result['name']);
        $this->assertEquals($isKeyActive, $result['is_active']);
        $this->assertEquals($keyPermitted, $result['permitted']);
        $this->assertEquals($projectId, $result['project_id']);
    }

    /**
     * Create Keen Client
     * @param array $response
     * @return KeenIOClient
     */
    protected function getClient(array $response)
    {
        $queue = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($response))
        ]);
        $handler = HandlerStack::create($queue);

        return KeenIOClient::factory(array(
            'projectId' => $_SERVER['PROJECT_ID'],
            'masterKey' => $_SERVER['MASTER_KEY'],
            'writeKey' => $_SERVER['WRITE_KEY'],
            'readKey' => $_SERVER['READ_KEY'],
            'version' => $_SERVER['API_VERSION'],
            'handler' => $handler
        ));
    }
}
