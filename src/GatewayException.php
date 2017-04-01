<?php

namespace Larium\Pay;

use RuntimeException;

class GatewayException extends RuntimeException
{
    public static function notImplemented($transaction, $gateway)
    {
        return new self(
            sprintf("Gateway `%s` does not support `%s` transaction", $gateway, $transaction)
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
