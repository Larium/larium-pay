<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Client\RestClient;
use Larium\Pay\Transaction\Purchase;

class Worldpay extends RestGateway
{
    const URI = 'https://api.worldpay.com/v1/';

    const TOKEN = 'tokens';
    const PURCHASE = 'orders';

    private $payload = [];

    protected function getBaseUri()
    {
        return self::URI;
    }

    protected function purchase(Purchase $transaction)
    {
        $this->sale($transaction);

        $payload = json_encode($this->payload);

        $response = $this->getRestClient(self::PURCHASE)
            ->post($payload);

        return $this->getResponse($response);

        #$client = $this->getRestClient(self::SALE);

        #$response = $client->post($this->payload);

        #return $this->getResponse($response);
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

        return isset($responseBody['orderCode'])
            ? $responseBody['orderCode']
            : $responseBody['token'];
    }

    protected function errorCode(array $responseBody)
    {
        return isset($responseBody['customCode'])
            ? $responseBody['customCode']
            : null;
    }

    protected function responseCode(array $responseBody)
    {
        return null;
    }
}
