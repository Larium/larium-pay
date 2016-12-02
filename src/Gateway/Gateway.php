<?php

namespace Larium\Pay\Gateway;

use Larium\Pay\Response;
use Larium\Pay\ParamsBag;
use Larium\Pay\GatewayException;
use Larium\Pay\Transaction\Void;
use Larium\Pay\Transaction\Query;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Initial;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Retrieve;
use Larium\Pay\Transaction\Authorize;
use Larium\Pay\Transaction\Transaction;
use Larium\Pay\Exception\NotImplementedException;

abstract class Gateway
{
    protected $sandbox;

    protected $options;

    private $responseCallback;

    final public function __construct(array $options = [])
    {
        $this->options = new ParamsBag($options);

        $this->sandbox = $this->options->get('sandbox', false);
    }

    public function execute(
        Transaction $transaction,
        callable $responseCallback = null
    ) {
        $transaction->commit();

        $this->responseCallback = $responseCallback;

        switch (true) {
            case $transaction instanceof Purchase:
                return $this->purchase($transaction);
            case $transaction instanceof Authorize:
                return $this->authorize($transaction);
            case $transaction instanceof Capture:
                return $this->capture($transaction);
            case $transaction instanceof Refund:
                return $this->refund($transaction);
            case $transaction instanceof Void:
                return $this->void($transaction);
            case $transaction instanceof Retrieve:
                return $this->retrieve($transaction);
            case $transaction instanceof Initial:
                return $this->initiate($transaction);
            case $transaction instanceof Query:
                return $this->query($transaction);
            default:
                throw new \RuntimeException(
                    sprintf('Invalid transaction type `%s`', get_class($transaction))
                );
        }
    }

    protected function purchase(Purchase $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function authorize(Authorize $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function capture(Capture $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function refund(Refund $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function void(Void $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function retrieve(Retrieve $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function initiate(Initial $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    protected function query(Query $transaction)
    {
        throw GatewayException::notImplemented(__FUNCTION__);
    }

    /**
     * Creates and return the response from gateway.
     *
     * @param bool   $success       Whether response was success or not.
     * @param string $message       The message theat describe response status or
     *                              reason.
     * @param string $transactionId The unique identifier from transaction.
     * @param string $errorCode     The error code if transaction was failed.
     * @param string $responsecCode The ISO 8583 response code. Not always
     *                              available.
     * @param array  $payload       An associative array of the response from
     *                              gateway including http status and headers.
     * @param string $rawResponse   The raw response of gateway.
     * @param string $rawRequest    The raw request to gateway.
     *
     * @return Larium\Pay\Response|mixed Response may be a user response if $responseCallback
     *                                   param is used in execute method
     */
    protected function createResponse(
        $success,
        $message,
        $transactionId,
        $errorCode = '0',
        $responseCode = null,
        array $payload = [],
        $rawResponse = null,
        $rawRequest = null
    ) {
        $response = new Response(
            $success,
            $message,
            $transactionId,
            $errorCode,
            $responseCode,
            $payload,
            $rawResponse,
            $rawRequest
        );

        if ($this->responseCallback) {
            return call_user_func_array(
                $this->responseCallback,
                [
                    $response,
                    $payload
                ]
            );
        }

        return $response;
    }
}
