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

    public function testSetValidApiKey()
    {
        $apiKey = '12345';
        KeenIO::setApiKey($apiKey);
        $this->assertEquals($apiKey, KeenIO::getApiKey(), 'API Key was set OK');
    }

    public function testSetInvalidApiKey()
    {
        $apiKey = '1-2345';
        $this->setExpectedException('Exception', "API Key '1-2345' contains invalid characters or spaces");
        KeenIO::setApiKey($apiKey);
    }

    public function testInvalidCollectionName()
    {
        KeenIO::configure('12345', '12345');
        $this->setExpectedException('Exception', "Collection name '1-2-3' contains invalid characters or spaces.");
        KeenIO::addEvent('1-2-3', null);
    }

    public function testValidCollectionName()
    {
        $adaptor = $this->getMockAdaptor('{ "created": true }');

        KeenIO::configure('12345', '12345', $adaptor);

        $result = KeenIO::addEvent('purchase', array());
        $this->assertTrue($result);
    }

    /**
     * create a mock http adaptor
     *
     * @param $content
     * @return \KeenIO\Service\AdaptorInterface
     */
    private function getMockAdaptor($content)
    {
        $adaptor = $this->getMockBuilder('\KeenIO\Service\AdaptorInterface')
            ->setMethods(array('doPost'))
            ->setMockClassName('HttpAdaptor')
            ->getMock();

        $adaptor->expects($this->once())
            ->method('doPost')
            ->will($this->returnValue($content));
        return $adaptor;
    }
}