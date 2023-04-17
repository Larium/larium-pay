<?php

declare(strict_types=1);

namespace Larium\Pay;

use Larium\CreditCard\CreditCard;
use Larium\Pay\Client\XmlClient;
use Larium\Pay\Gateway\Gateway;
use Larium\Pay\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    public function getFixture(string $gateway): array
    {
        $ini = parse_ini_file(__DIR__ . "/fixtures.ini", true);

        $data = new \ArrayIterator($ini);

        return $data[$gateway];
    }

    protected function assertSuccess(Response $response): void
    {
        $this->assertTrue($response->isSuccess());
    }

    protected function assertFailure(Response $response): void
    {
        $this->assertFalse($response->isSuccess());
    }

    protected function mockRestGatewayClient(
        string $gatewayClassName,
        array $options,
        array $response
    ): Gateway|MockObject {
        return $this->mockGatewayClient(
            'Larium\Pay\Client\RestClient',
            $gatewayClassName,
            $options,
            $response
        );
    }

    protected function mockXmlGatewayClient(
        string $gatewayClassName,
        array $options,
        array $response
    ): XmlClient|MockObject {
        return $this->mockGatewayClient(
            'Larium\Pay\Client\XmlClient',
            $gatewayClassName,
            $options,
            $response
        );
    }

    protected function mockGatewayClient(
        string $clientClass,
        string $gatewayClassName,
        array $options,
        array $response
    ): Gateway|MockObject {
        $clientStub = $this->getMockBuilder($clientClass)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolveResponse', 'discoverClient'])
            ->getMock();

        $clientStub->method('resolveResponse')
            ->willReturn($response);

        $clientStub->method('discoverClient')
            ->willReturn(new \Http\Mock\Client());

        $gatewayStub = $this->getMockBuilder($gatewayClassName)
            ->setConstructorArgs([$options])
            ->onlyMethods(['createClient', 'query'])
            ->getMock();

        $gatewayStub->method('createClient')
            ->willReturn($clientStub);

        return $gatewayStub;
    }

    protected function getCard(): CreditCard
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
        return substr(uniqid(strval(random_int(0, 30)), true), 0, 10);
    }
}
