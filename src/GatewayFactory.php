<?php

declare(strict_types=1);

namespace Larium\Pay;

class GatewayFactory
{
    public const GATEWAY_NAMESPACE = 'Larium\\Pay\\Gateway\\';

    private static $gateways = [];

    private function __construct()
    {
    }

    /**
     * Register a gateway with given name and class name.
     *
     * @throws Larium\Pay\GatewayException
     *
     * @param string $name The unique name for the gateway.
     * @param string $className The full class name of gateway.
     * @return void
     */
    public static function register($name, $className)
    {
        if (array_key_exists($name, self::$gateways)) {
            throw GatewayException::gatewayAlreadyRegistered($name);
        }
        self::$gateways[$name] = $className;
    }

    /**
     * Return an instance of a Gateway based on given name.
     *
     * @param string $name The registered name of Gateway
     * @param array $options Options for gateway
     * @return \Larium\Pay\Gateway\Gateway
     */
    public static function create($name, array $options = [])
    {
        $gateway = self::GATEWAY_NAMESPACE . $name;

        if (class_exists($gateway)) {
            return new $gateway($options);
        }

        if (array_key_exists($name, self::$gateways)) {
            $gateway = self::$gateways[$name];

            return new $gateway($options);
        }

        throw GatewayException::invalidGatewayName($name);
    }
}
