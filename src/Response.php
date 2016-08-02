<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

class Response
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string|int
     */
    private $errorCode;

    /**
     * @var string
     */
    private $responseCode;

    /**
     * @var array
     */
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

    /**
     * Returns whether response is success or not.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Returns the unique transaction id from gateway.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Returns the error code from gateway, if exists.
     *
     * @return string|int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Returns a human readable message for current transaction response,
     * either on success or failed transactions.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the credit card response code from gateway, if exits.
     *
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Returns any gateway response element as an associative array.
     * Array may contain nested elements.
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
