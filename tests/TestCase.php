<?php

namespace Larium\Pay;

use Larium\Pay\Response;
use Larium\CreditCard\CreditCard;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getFixture($gateway)
    {
        $ini = parse_ini_file(__DIR__ . "/fixtures.ini", true);

        $data = new \ArrayIterator($ini);

        return $data[$gateway];
    }

    protected function assertSuccess(Response $response)
    {
        $this->assertTrue($response->isSuccess());
    }

    protected function assertFailure(Response $response)
    {
        $this->assertFalse($response->isSuccess());
    }

    protected function mockGatewayClient($gatewayClassName, $options, array $response)
    {
        $clientStub = $this->getMockBuilder('Larium\Pay\Client\RestClient')
            ->disableOriginalConstructor()
            ->setMethods(['resolveResponse', 'discoverClient'])
            ->getMock();

        $clientStub->method('resolveResponse')
            ->will($this->returnValue($response));

        $clientStub->method('discoverClient')
            ->willReturn(new \Http\Mock\Client());

        $gatewayStub = $this->getMockBuilder($gatewayClassName)
            ->setConstructorArgs([$options])
            ->setMethods(['createRestClient'])
            ->getMock();

        $gatewayStub->method('createRestClient')
            ->will($this->returnValue($clientStub));

        return $gatewayStub;
    }

    protected function getCard()
    {
        return new CreditCard([
            'name' => 'JOHN DOE',
            'number' => '4111111111111111',
            'month' => '01',
            'year' => date('Y') + 1,
            'cvv' => '123',
        ]);
    }
}
