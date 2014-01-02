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
            'readKey'   => 'testMasterKey',
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
    public function testWriteKeySetter()
    {
        $client = $this->getServiceBuilder()->get('keen-io');
        $client->setWriteKey('testWriteKey');

        $this->assertEquals('testWriteKey', $client->getConfig('writeKey'));
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
    public function testGetScopedKey()
    {
        $client = KeenIOClient::factory(array(
            'masterKey' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'
        ));

        $filter = array('property_name' => 'id', 'operator' => 'eq', 'property_value' => '123');
        $filters = array($filter);
        $allowed_operations = array('read');

        $scopedKey = $client->getScopedKey($filters, $allowed_operations);

        $result = $client->decryptScopedKey($scopedKey);
        $expected = array('filters' => $filters, 'allowed_operations' => $allowed_operations);

        $this->assertEquals($expected, $result);
    }
}
