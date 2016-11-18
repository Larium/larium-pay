<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Client\RestClient;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Authorize;

class Worldpay extends RestGateway
{
    const URI = 'https://api.worldpay.com/v1/';

    const SALE = 'orders';
    const TOKEN = 'tokens';
    const REFUND = 'orders/%s/refund';
    const CAPTURE = 'orders/%s/capture';

    const CURRENCY = 'GBP';

    private $payload = [];

    protected function getBaseUri()
    {
        return self::URI;
    }

    protected function purchase(Purchase $transaction)
    {
        $this->sale($transaction);

        $payload = json_encode($this->payload);

        $response = $this->getRestClient(self::SALE)
            ->post($payload);

        return $this->getResponse($response);
    }

    protected function authorize(Authorize $transaction)
    {
        $this->sale($transaction);

        $this->payload['authorizeOnly'] = true;
        $payload = json_encode($this->payload);

        $response = $this->getRestClient(self::SALE)
            ->post($payload);

        return $this->getResponse($response);
    }

    protected function capture(Capture $transaction)
    {
        $payload = [
            'captureAmount' => $transaction->getAmount(),
        ];

        $payload = json_encode($payload);

        $resource = sprintf(self::CAPTURE, $transaction->getId());

        $response = $this->getRestClient($resource)
            ->post($payload);

        return $this->getResponse($response);
    }

    protected function refund(Refund $transaction)
    {
        $payload = [
            'refundAmount' => $transaction->getAmount(),
        ];

        $payload = json_encode($payload);

        $resource = sprintf(self::REFUND, $transaction->getId());

        $response = $this->getRestClient($resource)
            ->post($payload);

        return $this->getResponse($response);
    }

    private function sale($transaction)
    {
        $tokenResponse = $this->createToken($transaction);

        if (!$tokenResponse->isSuccess()) {
            return $tokenResponse;
        }

        $token = $tokenResponse->getTransactionId();

        $this->createOrder($transaction, $token);
    }

    private function createOrder($transaction, $token)
    {
        $this->payload = [
            'token' => $token,
            'amount' => $transaction->getAmount(),
            'currencyCode' => $transaction->getCurrency() ?: self::CURRENCY,
            'orderDescription' => $transaction->getDescription(),
            'settlementCurrency' => $transaction->getCurrency() ?: self::CURRENCY,
        ];

        $this->payload = array_filter($this->payload);
    }

    private function createToken($transaction)
    {
        $card = $transaction->getCardReference();
        $payload = [
            'reusable' => 'false',
            'paymentMethod' => [
                'name' => $card->getName(),
                'expiryMonth' => $card->getMonth(),
                'expiryYear' => $card->getYear(),
                'cardNumber' => $card->getNumber(),
                'cvc' => $card->getCvv(),
                'type' => 'Card',
            ],
            'clientKey' => $this->options['client_key']
        ];

        $payload = json_encode($payload);

        $client = $this->getRestClient(self::TOKEN);
        $response = $client->post($payload);

        return $this->getResponse($response);
    }

    protected function authenticate(Client $client)
    {
        $client->setHeaderAuthentication('Authorization', $this->options['service_key']);
    }

    protected function createRestClient($uri, $resource)
    {
        $headers = [
            'Content-type' => 'application/json',
        ];

        return new RestClient($uri, $resource, $headers);
    }

    protected function success(array $response)
    {
        if (isset($response['body']['paymentStatusReason'])
            || isset($response['body']['iso8583Status'])
        ) {
            return false;
        }

        $statusCode = isset($response['body']['httpStatusCode'])
            ? $response['body']['httpStatusCode']
            : 200;

        return $statusCode == 200;
    }

    protected function message(array $response)
    {
        if (isset($response['body']['paymentStatus'])) {
            return $response['body']['paymentStatus'];
        }

        if (isset($response['body']['message'])) {
            return $response['body']['message'];
        }

        return null;
    }

    protected function transactionId(array $response)
    {
        if (!$this->success($response)) {
            return null;
        }

        if (isset($response['body']['orderCode'])) {
            return $response['body']['orderCode'];
        }

        if (isset($response['body']['token'])) {
            return $response['body']['token'];
        }
    }

    protected function errorCode(array $response)
    {
        return isset($response['body']['customCode'])
            ? $response['body']['customCode']
            : null;
    }

    protected function responseCode(array $response)
    {
        if (isset($response['body']['iso8583Status'])
            && preg_match("/(\d+)\s-/", $response['body']['iso8583Status'], $m)
        ) {
            return str_pad($m[1], 2, '0', STR_PAD_LEFT);
        }

        return null;
    }
}
