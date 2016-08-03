<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

use InvalidArgumentException;

class GatewayFactory
{
    const GATEWAY_NAMESPACE = 'Larium\\Pay\\Gateway\\';

    private static $gateways = [];

    private function __construct()
    {
    }

    public static function register($name, $className)
    {
        self::$gateways[$name] = $className;
    }

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

        throw new InvalidArgumentException(
            sprintf('Could not resolve gateway with name `%s`', $name)
        );
    }
}
