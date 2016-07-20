<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Transaction\Void;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Authorize;

class Everypay extends RestGateway
{
    const URI = 'https://api.everypay.gr';

    const SALE = 'payments';
    const CREDIT = 'payments/refund/%s';
    const CAPTURE = 'payments/capture/%s';

    protected function getBaseUri()
    {
        return self::URI;
    }

    protected function purchase(Purchase $transaction)
    {
        $payload = $this->sale($transaction);

        $client = $this->getRestClient(self::SALE);

        $response = $client->post($payload);

        return $this->getResponse($response);
    }

    protected function authorize(Authorize $transaction)
    {
        $payload = $this->sale($transaction);

        $payload['capture'] = "0";

        $client = $this->getRestClient(self::SALE);

        $response = $client->post($payload);

        return $this->getResponse($response);
    }

    protected function capture(Capture $transaction)
    {
        $response = $this->getRestClient(self::CAPTURE)->put($transaction->getId());

        return $this->getResponse($response);
    }

    protected function refund(Refund $transaction)
    {
        $response = $this->getRestClient(self::CREDIT)->put($transaction->getId());

        return $this->getResponse($response);
    }

    protected function void(Void $transaction)
    {
        $response = $this->getRestClient(self::CREDIT)->put($transaction->getId());

        return $this->getResponse($response);
    }

    private function sale($transaction)
    {
        $card = $transaction->getCardReference();
        $payload = [
            'holder_name' => $card->getName(),
            'card_number' => $card->getNumber(),
            'expiration_year' => $card->getYear(),
            'expiration_month' => $card->getMonth(),
            'cvv' => $card->getCvv(),
            'amount' => $transaction->getAmount(),
        ];

        return $payload;
    }

    protected function authenticate(Client $client)
    {
        $client->setBasicAuthentication($this->options['secret_key'], null);
    }

    protected function success(array $responseBody)
    {
        return !isset($responseBody['error']);
    }

    protected function message(array $responseBody)
    {
        return $this->success($responseBody)
            ? $responseBody['status']
            : $responseBody['error']['message'];
    }

    protected function transactionId(array $responseBody)
    {
        return $this->success($responseBody)
            ? $responseBody['token']
            : null;
    }

    protected function errorCode(array $responseBody)
    {
        return $this->success($responseBody)
            ? 0
            : $responseBody['error']['code'];
    }

    protected function responseCode(array $responseBody)
    {
        return null;
    }
}
