<?php
namespace KeenIO;

use KeenIO\Service\KeenIO;

/**
 * Class KeenIOTest
 * @package KeenIO
 */
class KeenIOTest extends \PHPUnit_Framework_TestCase
{

    public function testAddEventIfNotConfigured()
    {
        $this->setExpectedException('Exception', 'Keen IO has not been configured');
        KeenIO::addEvent('12335', null);
    }

    public function testSetValidProjectId()
    {
        $projectId = '12345';
        KeenIO::setProjectId($projectId);
        $this->assertEquals($projectId, KeenIO::getProjectId(), 'Project ID was set OK');
    }

    public function testSetInvalidProjectId()
    {
        $this->setExpectedException('Exception', "Project ID '1-3-5' contains invalid characters or spaces.");
        KeenIO::setProjectId('1-3-5');
    }

    public function testSetValidWriteKey()
    {
        $key = '12345';
        KeenIO::setWriteKey($key);
        $this->assertEquals($key, KeenIO::getWriteKey(), 'Write Key was set OK');
    }

    public function testSetInvalidWriteKey()
    {
        $key = '1-2345';
        $this->setExpectedException('Exception', "Write Key '1-2345' contains invalid characters or spaces");
        KeenIO::setWriteKey($key);
    }

    public function testSetValidReadKey()
    {
        $key = '12345';
        KeenIO::setReadKey($key);
        $this->assertEquals($key, KeenIO::getReadKey(), 'Write Key was set OK');
    }

    public function testSetInvalidReadKey()
    {
        $key = '1-2345';
        $this->setExpectedException('Exception', "Read Key '1-2345' contains invalid characters or spaces");
        KeenIO::setReadKey($key);
    }

    public function testInvalidCollectionName()
    {
        KeenIO::configure('projectId', 'writeKey', 'readKey');
        $this->setExpectedException('Exception', "Collection name '_1-2-3' contains invalid characters.");
        KeenIO::addEvent('_1-2-3', null);
    }

    public function testGetHttpAdapter()
    {
        $adapter = KeenIO::getHttpAdapter();
        $this->assertInstanceOf('KeenIO\Http\Adapter\AdapterInterface', $adapter, 'Adapter is not a valid client');
    }

    public function testValidCollectionName()
    {
        $adapter = $this->getMockAdapter('{ "created": true }');

        KeenIO::configure('projectId', 'writeKey', 'readKey');
        KeenIO::setHttpAdapter($adapter);

        $result = KeenIO::addEvent('purchase', array());
        $this->assertTrue($result);
    }

    public function testGetScopedKey()
    {
        $apiKey = "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
        $filter = array('property_name' => 'id', 'operator' => 'eq', 'property_value' => '123');
        $filters = array($filter);
        $allowed_operations = array('read');

        $scopedKey = KeenIO::getScopedKey($apiKey, $filters, $allowed_operations);

        $result = KeenIO::decryptScopedKey($apiKey, $scopedKey);
        $expected = array('filters' => $filters, 'allowed_operations' => $allowed_operations);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for testing collection name validation
     *
     * @return array
     */
    public function collectionNameProvider()
    {
        return array(
            array('testcollection', true),
            array('test_collection_', true),
            array('_test_collection', false), // Cannot start with an underscore
            array('qwertyuiop1234567890asdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbnm123456', false), // Cannot be more than 64 characters
            array('téstcøllection', false), // Must only contain ASCII characters
            array('test$collection', false), // Cannot contain a $
            array('.testcollection', false), // Cannot start or end with a period (.)
            array('testcollection.', false), // Cannot start or end with a period (.)
            array('test.collection', true),
            array('test collection', true), // Spaces are allowed
            array(null, false), // Cannot be a null value
            array('', false), // Cannot be a null value
        );
    }

    /**
     *
     * @param string  $collectionName
     * @param boolean $isValid
     *
     * @dataProvider collectionNameProvider
     */
    public function testValidateCollectionName($collectionName, $isValid)
    {
        $this->assertSame($isValid, KeenIO::validateCollectionName($collectionName));
    }

    /**
     * create a mock http adapter
     *
     * @param $content
     * @return \KeenIO\Http\Adapter\AdapterInterface
     */
    private function getMockAdapter($content)
    {
        $adapter = $this->getMockBuilder('\KeenIO\Http\Adapter\AdapterInterface')
            ->setMethods(array('doPost'))
            ->setMockClassName('HttpAdapter')
            ->getMock();

        $adapter->expects($this->once())
            ->method('doPost')
            ->will($this->returnValue($content));

        return $adapter;
    }

}
