<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Response;

class Response
{
    /**
     * @var bool
     */
    private $success;

    private $message;

    public function __construct($success, $message)
    {

    }

    public function isSuccess()
    {
        return $success;
    }

    public function getTransactionId()
    {

    }

    public function getErrorCode()
    {

    }

    public function getMessage()
    {

    }

    public function getResponseCode()
    {

    }
}
