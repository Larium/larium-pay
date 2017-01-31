<?php

namespace Larium\Pay;

use RuntimeException;

class GatewayException extends RuntimeException
{
    public static function notImplemented($method)
    {
        return new self(
            sprintf("Method `%s` is not supported!", $method)
        );
    }

    public static function invalidGatewayName($name)
    {
        return new self(
            sprintf("Could not resolve gateway with name `%s`", $name)
        );
    }

    public static function gatewayAlreadyRegistered($name)
    {
        return new self(
            sprintf("Gateway with name `%s` is already registered", $name)
        );
    }
}
