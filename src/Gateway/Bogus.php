<?php

namespace Larium\Pay\Gateway;

use Larium\Pay\Transaction\Query;
use Larium\Pay\Transaction\Cancel;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Initial;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Retrieve;
use Larium\Pay\Transaction\Authorize;

class Bogus extends Gateway
{
    protected function purchase(Purchase $transaction)
    {
        $response = [
            'status' => 200,
            'headers' => [],
            'body' => null,
            'raw_response' => null,
            'raw_request' => null,
        ];

        return $this->getResponse($response);
    }

    protected function authorize(Authorize $transaction)
    {
    }

    protected function capture(Capture $transaction)
    {
    }

    protected function refund(Refund $transaction)
    {
    }

    protected function cancel(Cancel $transaction)
    {
    }

    protected function retrieve(Retrieve $transaction)
    {
    }

    protected function initiate(Initial $transaction)
    {
    }

    protected function query(Query $transaction)
    {
    }

    protected function success(array $response)
    {
    }

    protected function message(array $response)
    {
    }

    protected function transactionId(array $response)
    {
    }

    protected function errorCode(array $response)
    {
    }

    protected function responseCode(array $response)
    {
    }

    private function getResponse(array $response)
    {
        return $this->createResponse(
            $this->success($response),
            $this->message($response),
            $this->transactionId($response),
            $this->errorCode($response),
            $this->responseCode($response),
            $response['body'],
            $response['raw_response'],
            $response['raw_request']
        );
    }
}
