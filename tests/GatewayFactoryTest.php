<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

class GatewayFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessGatewayCreation()
    {
        $gateway = GatewayFactory::create('Everypay', []);

        $this->assertInstanceOf(
            'Larium\Pay\Gateway\Everypay',
            $gateway
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Could not resolve gateway with name `UnknownGateway`
     */
    public function testFailedGatewayCreation()
    {
        $gateway = GatewayFactory::create('UnknownGateway', []);
    }
}
