<?php

namespace Larium\Pay;

class GatewayFactory
{
    const GATEWAY_NAMESPACE = 'Larium\\Pay\\Gateway\\';

    private static $gateways = [];

    private function __construct()
    {
    }

    /**
     * Register a gateway with given name and class name.
     *
     * @param string $name The name which factory will look for gateway.
     * @param string $className The full class name to instatiate gateway.
     * @return void
     */
    public static function register($name, $className)
    {
        self::$gateways[$name] = $className;
    }

    /**
     * Return an instance of a Gateway based on given name.
     *
     * @param string $name The registered name of Gateway
     * @param array $options Options for gateway
     * @return Larium\Pay\Gateway\Gateway
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
