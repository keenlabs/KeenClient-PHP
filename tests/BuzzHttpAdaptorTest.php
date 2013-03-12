<?php
namespace KeenIO;

use KeenIO\Http\Client\Buzz as BuzzHttpAdaptor;
use KeenIO\Service\KeenIO;

/**
 * Class KeenIOTest
 * @package KeenIO
 */
class BuzzHttpAdaptorTest extends \PHPUnit_Framework_TestCase
{
    public function testDoPost()
    {
        $browser = $this->getMockBrowser('12345');
        $adaptor = new BuzzHttpAdaptor('apikey', $browser);

        $this->assertEquals('12345', $adaptor->doPost('http://example.com', array('1' => 1)));
    }

    /**
     * @param $content
     * @return \Buzz\Browser
     */
    private function getMockBrowser($content)
    {
        $response = $this->getMockBuilder('Response')
            ->setMethods(array('getContent'))
            ->getMock();

        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($content));

        $adaptor = $this->getMockBuilder('\Buzz\Browser')
            ->setMethods(array('post'))
            ->getMock();

        $adaptor->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));
        return $adaptor;
    }
}
