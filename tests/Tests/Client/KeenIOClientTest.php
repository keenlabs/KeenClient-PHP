<?php

namespace KeenIO\Tests\Client;

use KeenIO\Client\KeenIOClient;
use Guzzle\Tests\GuzzleTestCase;

class KeenIOClientTest extends GuzzleTestCase
{
	/**
	 * Check that a Keen IO Client is instantiated properly
	 */
	public function testFactoryReturnsClient( )
	{
		$config = array(
			'projectId' => 'testProjectId',
			'masterKey'	=> 'testMasterKey',
			'readKey'	=> 'testMasterKey',
			'writeKey'	=> 'testWriteKey',
			'version'	=> '3.0'
		);

		$client = KeenIOClient::factory( $config );

		//Check that the Client is of the right type
		$this->assertInstanceOf( '\KeenIO\Client\KeenIOClient', $client );

		//Check that the pass config options match the client's config
		$this->assertEquals( $config['projectId'], $client->getConfig('projectId') );
		$this->assertEquals( $config['masterKey'], $client->getConfig('masterKey') );
		$this->assertEquals( $config['readKey'], $client->getConfig('readKey') );
		$this->assertEquals( $config['writeKey'], $client->getConfig('writeKey') );
	}

	/**
	 * @dataProvider		invalidClientConfigValues
	 * @expectedException	InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnInvalidConfigs( $masterKey, $readKey, $writeKey, $projectId, $version )
	{
		$config = array(
			'projectId' => $projectId,
			'masterKey'	=> $masterKey,
			'readKey'	=> $readKey,
			'writeKey'	=> $writeKey,
			'version'	=> $version
		);

		$client = KeenIOClient::factory( $config );
	}

	/**
	 * Tests the client setter method and that the value returned is correct
	 */
	public function testProjectIdSetter()
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setProjectId( 'testProjectId' );

		$this->assertEquals( 'testProjectId', $client->getConfig('projectId') );
	}

	/**
	 * @dataProvider		invalidValues
	 * @expectedException	InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidProjectId( $projectId )
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setProjectId( $projectId );
	}

	/**
	 * Tests the client setter method and that the value returned is correct
	 */
	public function testReadKeySetter()
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setReadKey( 'testReadKey' );

		$this->assertEquals( 'testReadKey', $client->getConfig('readKey') );
	}

	/**
	 * @dataProvider		invalidValues
	 * @expectedException	InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidReadKey( $readKey )
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setReadKey( $readKey );
	}

	/**
	 * Tests the client setter method and that the value returned is correct
	 */
	public function testWriteKeySetter()
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setWriteKey( 'testWriteKey' );

		$this->assertEquals( 'testWriteKey', $client->getConfig('writeKey') );
	}

	/**
	 * @dataProvider		invalidValues
	 * @expectedException	InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidWriteKey( $writeKey )
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setWriteKey( $writeKey );
	}

	/**
	 * Tests the client setter method and that the value returned is correct
	 */
	public function testMasterKeySetter()
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setMasterKey( 'testMasterKey' );

		$this->assertEquals( 'testMasterKey', $client->getConfig('masterKey') );
	}

	/**
	 * @dataProvider		invalidValues
	 * @expectedException	InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidMasterKey( $masterKey )
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setMasterKey( $masterKey );
	}

	/**
	 * Tests the client setter method and that the value returned is correct
	 */
	public function testVersionSetter()
	{
		$client = $this->getServiceBuilder()->get('keen-io');
		$client->setVersion( '3.0' );

		$this->assertEquals( '3.0', $client->getConfig('version') );
	}

	/**
	 * @dataProvider		invalidVersions
	 * @expectedException	InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidVersion( $version )
	{
		$client = KeenIOClient::factory( array() );

		$client->setVersion( $version );
	}

	/**
	 * Tests the creation of a Scoped Key
	 */
	public function testGetScopedKey()
	{
		$client = KeenIOClient::factory();

		$apiKey = "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
		$filter = array( 'property_name' => 'id', 'operator' => 'eq', 'property_value' => '123' );
		$filters = array( $filter );
		$allowed_operations = array( 'read' );

		$scopedKey = $client->getScopedKey( $apiKey, $filters, $allowed_operations );

		$result = $client->decryptScopedKey( $apiKey, $scopedKey );
		$expected = array( 'filters' => $filters, 'allowed_operations' => $allowed_operations );

		$this->assertEquals( $expected, $result );
	}

	/**
	 *	Invalid values used for testing setter methods on the Keen IO Client
	 */
	public function invalidValues()
	{
		return array( array( '!#1234567890.' ), array( '' ) );
	}

	/**
	 *	Invalid version values used for testing version setter
	 */
	public function invalidVersions()
	{
		return array( array( '' ), array( '99.0' ) );
	}

	/**
	 *	Invalid config values used for testing the factory method of the Keen IO Client
	 */
	public function invalidClientConfigValues()
	{
		return array( 
			array( '!#1234567890.', 'testReadAPIKey', 'testWriteAPIKey', 'testProjectId', '3.0' ),		//Invalid Master Key 
			array( '', 'testReadAPIKey', 'testWriteAPIKey', 'testProjectId', '3.0' ),					//Blank Master Key
			array( 'testMasterAPIKey', '!#1234567890.', 'testWriteAPIKey', 'testProjectId', '3.0' ),	//Invalid Read Key
			array( 'testMasterAPIKey', '', 'testWriteAPIKey', 'testProjectId', '3.0' ),					//Blank Read Key
			array( 'testMasterAPIKey', 'testReadAPIKey', '!#1234567890.', 'testProjectId', '3.0' ),		//Invalid Write Key
			array( 'testMasterAPIKey', 'testReadAPIKey', '', 'testProjectId', '3.0' ),					//Blank Write Key
			array( 'testMasterAPIKey', 'testReadAPIKey', 'testWriteAPIKey', '!#1234567890.', '3.0' ),	//Invalid Project Id
			array( 'testMasterAPIKey', 'testReadAPIKey', 'testWriteAPIKey', '', '3.0' ),				//Blank Project Id
			array( 'testMasterAPIKey', 'testReadAPIKey', 'testWriteAPIKey', 'testProjectId', '' )		//Blank Version
		);
	}
}
