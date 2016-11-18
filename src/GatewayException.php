<?php

namespace Larium\Pay;

class GatewayException extends \Exception
{
    public static function notImplemented($method)
    {
        return new Exception\NotImplementedException(
            sprintf("Method `%s` is not supported!", $method)
        );
    }

    public static function invalidGatewayName($name)
    {
        return new Exception\InvalidGatewayNameException(
            sprintf("Could not resolve gateway with name `%s`", $name)
        );
    }
}
