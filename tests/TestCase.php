<?php

namespace Larium\Pay;

use Larium\Pay\Response;
use Larium\CreditCard\CreditCard;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
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

    protected function mockRestGatewayClient($gatewayClassName, $options, array $response)
    {
        return $this->mockGatewayClient(
            'Larium\Pay\Client\RestClient',
            $gatewayClassName,
            $options,
            $response
        );
    }

    protected function mockXmlGatewayClient($gatewayClassName, $options, array $response)
    {
        return $this->mockGatewayClient(
            'Larium\Pay\Client\XmlClient',
            $gatewayClassName,
            $options,
            $response
        );
    }

    protected function mockGatewayClient($clientClass, $gatewayClassName, $options, array $response)
    {
        $clientStub = $this->getMockBuilder($clientClass)
            ->disableOriginalConstructor()
            ->setMethods(['resolveResponse', 'discoverClient'])
            ->getMock();

        $clientStub->method('resolveResponse')
            ->will($this->returnValue($response));

        $clientStub->method('discoverClient')
            ->willReturn(new \Http\Mock\Client());

        $gatewayStub = $this->getMockBuilder($gatewayClassName)
            ->setConstructorArgs([$options])
            ->setMethods(['createClient'])
            ->getMock();

        $gatewayStub->expects($this->any())
            ->method('createClient')
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

    protected function generateUniqueId()
    {
        return substr(uniqid(rand(), true), 0, 10);
    }
}
