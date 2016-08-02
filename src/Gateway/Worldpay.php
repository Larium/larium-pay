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
            'currencyCode' => $transaction->getCurrency(),
            'orderDescription' => $transaction->getDescription(),
            'settlementCurrency' => $transaction->getCurrency(),
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
        $client->addHeader('Authorization', $this->options['service_key']);
    }

    protected function createRestClient($uri, $resource)
    {
        $headers = [
            'Content-type: application/json',
        ];

        return new RestClient($uri, $resource, $headers);
    }

    protected function success(array $responseBody)
    {
        if (isset($responseBody['paymentStatusReason'])
            || isset($responseBody['iso8583Status'])
        ) {
            return false;
        }

        $statusCode = isset($responseBody['httpStatusCode'])
            ? $responseBody['httpStatusCode']
            : 200;

        return $statusCode == 200;
    }

    protected function message(array $responseBody)
    {
        if (isset($responseBody['paymentStatus'])) {
            return $responseBody['paymentStatus'];
        }

        if (isset($responseBody['message'])) {
            return $responseBody['message'];
        }

        return null;
    }

    protected function transactionId(array $responseBody)
    {
        if (!$this->success($responseBody)) {
            return null;
        }

        if (isset($responseBody['orderCode'])) {
            return $responseBody['orderCode'];
        }

        if (isset($responseBody['token'])) {
            return $responseBody['token'];
        }
    }

    protected function errorCode(array $responseBody)
    {
        return isset($responseBody['customCode'])
            ? $responseBody['customCode']
            : null;
    }

    protected function responseCode(array $responseBody)
    {
        if (isset($responseBody['iso8583Status'])
            && preg_match("/(\d+)\s-/", $responseBody['iso8583Status'], $m)
        ) {
            return str_pad($m[1], 2, '0', STR_PAD_LEFT);
        }

        return null;
    }
}
