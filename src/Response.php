<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

class Response
{
    /**
     * @var bool
     */
    private $success;

    private $message;

    private $transactionId;

    private $errorCode;

    private $responseCode;

    private $payload;

    /**
     * @param bool $success
     * @param string $message
     * @param string $transactionId
     * @param string $errorCode
     * @param string $responseCode
     * @param array $payload
     */
    public function __construct(
        $success,
        $message,
        $transactionId,
        $errorCode = '0',
        $responseCode = '00',
        array $payload = []
    ) {
        $this->success = $success;
        $this->message = $message;
        $this->transactionId = $transactionId;
        $this->errorCode = $errorCode;
        $this->responseCode = $responseCode;
        $this->payload = $payload;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
