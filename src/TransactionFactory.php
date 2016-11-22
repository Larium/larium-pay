<?php

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

    public static function void($id, array $extraOptions = [])
    {
        return new Transaction\VoidTransaction($id, $extraOptions);
    }
}
