<?php

declare(strict_types=1);

namespace Larium\Pay\Gateway;

use Larium\Pay\GatewayException;
use Larium\Pay\ParamsBag;
use Larium\Pay\Response;
use Larium\Pay\Transaction\Authorize;
use Larium\Pay\Transaction\Cancel;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Initial;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Query;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Retrieve;
use Larium\Pay\Transaction\ThreedSecureAuthenticate;
use Larium\Pay\Transaction\Transaction;
use Larium\Pay\Transaction\Transfer;

use function call_user_func_array;

abstract class Gateway
{
    protected bool $sandbox;

    protected ParamsBag $options;

    /**
     * @var callable|null
     */
    private $responseCallback;

    private array $transactionToMethod = [
        Query::class => 'query',
        Cancel::class => 'cancel',
        Refund::class => 'refund',
        Capture::class => 'capture',
        Initial::class => 'initiate',
        Transfer::class => 'transfer',
        Purchase::class => 'purchase',
        Retrieve::class => 'retrieve',
        Authorize::class => 'authorize',
        ThreedSecureAuthenticate::class => 'threedSecureAuthenticate',
    ];

    /**
     * Return whether the response is success or not.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return bool
     */
    abstract protected function success(array $response): bool;

    /**
     * Returns the message from gateway response.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string
     */
    abstract protected function message(array $response): string;

    /**
     * Returns th unique transaction id from gateway response.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string|null
     */
    abstract protected function transactionId(array $response): ?string;

    /**
     * Returns error code from gateway if exists.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string|null
     */
    abstract protected function errorCode(array $response): ?string;

    /**
     * Returns response code from card processing, if exists.
     * @link https://arch.developer.visa.com/vpp/documents/xml/Request_and_Response.html Example of response codes
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string|null
     */
    abstract protected function responseCode(array $response): ?string;

    final public function __construct(array $options = [])
    {
        $this->options = new ParamsBag($options);

        $this->sandbox = $this->options->get('sandbox', false);
    }

    public function execute(
        Transaction $transaction,
        callable $responseCallback = null
    ): mixed {
        $transaction->commit();

        $this->responseCallback = $responseCallback;

        foreach ($this->transactionToMethod as $class => $method) {
            if ($transaction instanceof $class) {
                return $this->$method($transaction);
            }
        }

        throw new \RuntimeException(
            sprintf('Invalid transaction type `%s`', get_class($transaction))
        );
    }

    protected function purchase(Purchase $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function authorize(Authorize $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function capture(Capture $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function refund(Refund $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function cancel(Cancel $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function retrieve(Retrieve $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function initiate(Initial $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function query(Query $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function threedSecureAuthenticate(ThreedSecureAuthenticate $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    protected function transfer(Transfer $transaction): mixed
    {
        throw GatewayException::notImplemented(get_class($transaction), get_class($this));
    }

    /**
     * Creates and return the response from gateway.
     *
     * @param bool   $success       Whether response was success or not.
     * @param string $message       The message theat describe response status or
     *                              reason.
     * @param string $transactionId The unique identifier from transaction.
     * @param string $errorCode     The error code if transaction was failed.
     * @param string $responseCode  The ISO 8583 response code. Not always
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
    ): mixed {
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
