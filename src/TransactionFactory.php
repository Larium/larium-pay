<?php

declare(strict_types=1);

namespace Larium\Pay;

use Larium\CreditCard\CreditCard;

class TransactionFactory
{
    public static function purchase($amount, CreditCard $card, array $extraOptions = [])
    {
        return new Transaction\PurchaseTransaction($amount, $card, $extraOptions);
    }

    public static function authorize($amount, CreditCard $card, array $extraOptions = [])
    {
        return new Transaction\AuthorizeTransaction($amount, $card, $extraOptions);
    }

    public static function capture($amount, $id, array $extraOptions = [])
    {
        return new Transaction\CaptureTransaction($amount, $id, $extraOptions);
    }

    public static function refund($amount, $id, array $extraOptions = [])
    {
        return new Transaction\RefundTransaction($amount, $id, $extraOptions);
    }

    public static function cancel($id, array $extraOptions = [])
    {
        return new Transaction\CancelTransaction($id, $extraOptions);
    }

    public static function retrieve($id)
    {
        return new Transaction\RetrieveTransaction($id);
    }

    public static function initiate(
        $amount,
        $successUri,
        $cancelUri,
        array $extraOptions = []
    ) {
        return new Transaction\InitialTransaction(
            $amount,
            $successUri,
            $cancelUri,
            $extraOptions
        );
    }

    public static function query(array $criteria)
    {
        return new Transaction\QueryTransaction($criteria);
    }

    public static function threedSecureAuthenticate($pares, $transactionId)
    {
        return new Transaction\ThreedSecureAuthenticateTransaction(
            $pares,
            $transactionId
        );
    }

    public static function transfer(
        $amount,
        $currency,
        $recipientIdentifier,
        array $extraOptions = []
    ) {
        return new Transaction\TransferTransaction(
            $amount,
            $currency,
            $recipientIdentifier,
            $extraOptions
        );
    }
}
