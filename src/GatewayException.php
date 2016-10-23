<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

class GatewayException extends \Exception
{
    public static function notImplemented($method)
    {
        return new Exception\NotImplementedException(
            sprintf("Method `%s` is not implemented!", $method)
        );
    }

    public static function invalidGatewayName($name)
    {
        return new Exception\InvalidGatewayNameException(
            sprintf("Could not resolve gateway with name `%s`", $name)
        );
    }
}
