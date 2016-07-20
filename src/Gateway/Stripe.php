<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Purchase;

class Stripe extends RestGateway
{
    const URI = 'https://api.stripe.com/v1';

    const PURCHASE = 'charges';
    const REFUND = 'refunds';

    protected function getBaseUri()
    {
        return self::URI;
    }

    protected function purchase(Purchase $transaction)
    {
        $card = $transaction->getCardReference();

        $payload = [
            'amount' => $transaction->getAmount(),
            'currency' => 'gbp',
            'source' => [
                'exp_month' => $card->getMonth(),
                'exp_year' => $card->getYear(),
                'number' => $card->getNumber(),
                'object' => 'card',
                'cvv' => $card->getCvv(),
            ]
        ];

        $response = $this->getRestClient(self::PURCHASE)
            ->post($payload);

        return $this->getResponse($response);
    }

    public function refund(Refund $transaction)
    {
        $payload = [
            'amount' => $transaction->getAmount(),
            'charge' => $transaction->getId()
        ];

        $response = $this->getRestClient(self::REFUND)
            ->post($payload);


        return $this->getResponse($response);
    }

    protected function authenticate(Client $client)
    {
        $client->setBasicAuthentication($this->options['sk'], null);
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
            ? $responseBody['id']
            : null;
    }

    protected function errorCode(array $responseBody)
    {
        return $this->success($responseBody)
            ? 0
            : $responseBody['error']['type'];
    }

    protected function responseCode(array $responseBody)
    {
        return null;
    }
}
