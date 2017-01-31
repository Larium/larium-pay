<?php

namespace Larium\Pay;

class GatewayFactoryTest extends \PHPUnit_Framework_TestCase
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
        $gateway = GatewayFactory::create('UnknownGateway', []);
    }
}
