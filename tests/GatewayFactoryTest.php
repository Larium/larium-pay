<?php

declare(strict_types=1);

namespace Larium\Pay;

use PHPUnit\Framework\TestCase;

class GatewayFactoryTest extends TestCase
{
    public function testSuccessGatewayCreation()
    {
        $gateway = GatewayFactory::create('Bogus', []);

        $this->assertInstanceOf(
            'Larium\Pay\Gateway\Bogus',
            $gateway
        );
    }

    /**
     * @expectedException Larium\Pay\GatewayException
     * @expectedExceptionMessage Could not resolve gateway with name `UnknownGateway`
     */
    public function testFailedGatewayCreation()
    {
        $this->expectException(GatewayException::class);
        $this->expectExceptionMessage('Could not resolve gateway with name `UnknownGateway`');
        GatewayFactory::create('UnknownGateway', []);
    }
}
