<?php

namespace KeenIO\Tests\Client;

use KeenIO\Client\KeenIOClient;

class KeenIOClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param $masterKey
	 * @param $readKey
	 * @param $writeKey
	 * @param $projectId
	 * @param $version
	 *
	 * @dataProvider provideConfigValues
	 */
	public function testFactoryReturnsClient( $masterKey, $readKey, $writeKey, $projectId, $version )
	{
		$config = array(
			'projectId' => $projectId,
			'masterKey'	=> $masterKey,
			'readKey'	=> $readKey,
			'writeKey'	=> $writeKey,
			'version'	=> $version
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
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnInvalidProjectId()
	{
		$config = array( 'projectId' => '!#1234567890.' );

		$client = KeenIOClient::factory( $config );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnBlankProjectId()
	{
		$config = array( 'projectId' => '' );

		$client = KeenIOClient::factory( $config );
	}

	public function testProjectIdSetter()
	{
		$projectId = "testProjectId";
		$client = KeenIOClient::factory( array() );

		$client->setProjectId( $projectId );

		$this->assertEquals( $projectId, $client->getConfig('projectId') );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidProjectId()
	{
		$client = KeenIOClient::factory( array() );

		$client->setProjectId( '!#1234567890.' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnBlankProjectId()
	{
		$client = KeenIOClient::factory( array() );

		$client->setProjectId( '' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnInvalidMasterKey()
	{
		$config = array( 'masterKey' => '!#1234567890.' );

		$client = KeenIOClient::factory( $config );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnBlankMasterKey()
	{
		$config = array( 'masterKey' => '' );

		$client = KeenIOClient::factory( $config );
	}

	public function testMasterKeySetter()
	{
		$masterKey = "testMasterKey";
		$client = KeenIOClient::factory( array() );

		$client->setMasterKey( $masterKey );

		$this->assertEquals( $masterKey, $client->getConfig('masterKey') );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidMasterKey()
	{
		$client = KeenIOClient::factory( array() );

		$client->setMasterKey( '!#1234567890.' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnBlankMasterKey()
	{
		$client = KeenIOClient::factory( array() );

		$client->setMasterKey( '' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnInvalidReadKey()
	{
		$config = array( 'readKey' => '!#1234567890.' );

		$client = KeenIOClient::factory( $config );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnBlankReadKey()
	{
		$config = array( 'readKey' => '' );

		$client = KeenIOClient::factory( $config );
	}

	public function testReadKeySetter()
	{
		$readKey = "testReadKey";
		$client = KeenIOClient::factory( array() );

		$client->setReadKey( $readKey );

		$this->assertEquals( $readKey, $client->getConfig('readKey') );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidReadKey()
	{
		$client = KeenIOClient::factory( array() );

		$client->setReadKey( '!#1234567890.' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnBlankReadKey()
	{
		$client = KeenIOClient::factory( array() );

		$client->setReadKey( '' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnInvalidWriteKey()
	{
		$config = array( 'writeKey' => '!#1234567890.' );

		$client = KeenIOClient::factory( $config );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnBlankWriteKey()
	{
		$config = array( 'writeKey' => '' );

		$client = KeenIOClient::factory( $config );
	}

	public function testWriteKeySetter()
	{
		$writeKey = "testWriteKey";
		$client = KeenIOClient::factory( array() );

		$client->setWriteKey( $writeKey );

		$this->assertEquals( $writeKey, $client->getConfig('writeKey') );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnInvalidWriteKey()
	{
		$client = KeenIOClient::factory( array() );

		$client->setWriteKey( '!#1234567890.' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnBlankWriteKey()
	{
		$client = KeenIOClient::factory( array() );

		$client->setWriteKey( '' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFactoryReturnsExceptionOnBlankVersion()
	{
		$config = array( 'version' => '' );

		$client = KeenIOClient::factory( $config );
	}

	public function testVersionSetter()
	{
		$version = "3.0";
		$client = KeenIOClient::factory( array() );

		$client->setVersion( $version );

		$this->assertEquals( $version, $client->getConfig('version') );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnBadVersion()
	{
		$version = '99.0';
		$client = KeenIOClient::factory( array() );

		$client->setVersion( $version );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetterReturnsExceptionOnBlankVersion()
	{
		$client = KeenIOClient::factory( array() );

		$client->setVersion( '' );
	}

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

	public function provideConfigValues()
	{
		return array( array( 'testMasterAPIKey', 'testReadAPIKey', 'testWriteAPIKey', 'testProjectId', '3.0' ) );
	}
}
