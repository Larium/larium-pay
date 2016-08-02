<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Authorize;

class Stripe extends RestGateway
{
    const URI = 'https://api.stripe.com/v1';

    const REFUND = 'refunds';
    const CAPTURE = 'charges/%s/capture';
    const PURCHASE = 'charges';

    private $payload;

    protected function getBaseUri()
    {
        return self::URI;
    }

    protected function purchase(Purchase $transaction)
    {
        $this->sale($transaction);

        $response = $this->getRestClient(self::PURCHASE)
            ->post($this->payload);

        return $this->getResponse($response);
    }

    protected function authorize(Authorize $transaction)
    {
        $this->sale($transaction);
        $this->payload['capture'] = 'false';

        $response = $this->getRestClient(self::PURCHASE)
            ->post($this->payload);

        return $this->getResponse($response);
    }

    protected function capture(Capture $transaction)
    {
        $this->payload = [
            'amount' => $transaction->getAmount(),
        ];

        $resource = sprintf(self::CAPTURE, $transaction->getId());

        $response = $this->getRestClient($resource)
            ->post($this->payload);

        return $this->getResponse($response);
    }

    protected function refund(Refund $transaction)
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

    private function sale($transaction)
    {
        $card = $transaction->getCardReference();

        $this->payload = [
            'amount' => $transaction->getAmount(),
            'currency' => strtolower($transaction->getCurrency()),
        ];

        $this->setPaySource($card);
        $this->setAddress($transaction);
    }

    private function setPaySource($card)
    {
        if ($card->getToken() !== null) {
            $source = [
                'customer' => $card->getToken(),
            ];

            return $this->payload = array_merge($this->payload, $source);
        }

        $source = [
            'source' => [
                'exp_month' => $card->getMonth(),
                'exp_year' => $card->getYear(),
                'number' => $card->getNumber(),
                'object' => 'card',
                'cvv' => $card->getCvv(),
            ]
        ];

        return $this->payload = array_merge($this->payload, $source);
    }

    private function setAddress($transaction)
    {
        $address = $transaction->getAddress();
        $extra = $transaction->getExtraOptions();
        $shipping = [
            'shipping' => [
                'address' => [
                    'city' => $address->get('city'),
                    'country' => $address->get('country'),
                    'line1' => $address->get('address1'),
                    'line2' => $address->get('address2'),
                    'postal_code' => $address->get('zip'),
                    'state' => $address->get('state'),
                ],
                'carrier' => $extra->get('carrier'),
                'name' => $address->get('name'),
                'phone' => $address->get('phone'),
                'tracking_number' => $extra->get('tracking_number'),
            ]
        ];

        $this->payload = array_merge($this->payload, $shipping);
    }
}

