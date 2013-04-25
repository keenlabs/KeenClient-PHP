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
        $this->setExpectedException('Exception', "Collection name '1-2-3' contains invalid characters or spaces.");
        KeenIO::addEvent('1-2-3', null);
    }

    public function testGetHttpAdaptor()
    {
        $adaptor = KeenIO::getHttpAdaptor();
        $this->assertInstanceOf('KeenIO\Http\Adaptor\Buzz', $adaptor, 'Adaptor is not a Buzz client');
    }

    public function testValidCollectionName()
    {
        $adaptor = $this->getMockAdaptor('{ "created": true }');

        KeenIO::configure('projectId', 'writeKey', 'readKey');
        KeenIO::setHttpAdaptor($adaptor);

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
     * create a mock http adaptor
     *
     * @param $content
     * @return \KeenIO\Http\Adaptor\AdaptorInterface
     */
    private function getMockAdaptor($content)
    {
        $adaptor = $this->getMockBuilder('\KeenIO\Http\Adaptor\AdaptorInterface')
            ->setMethods(array('doPost'))
            ->setMockClassName('HttpAdaptor')
            ->getMock();

        $adaptor->expects($this->once())
            ->method('doPost')
            ->will($this->returnValue($content));

        return $adaptor;
    }

}
