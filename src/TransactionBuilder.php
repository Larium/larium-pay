<?php

namespace Larium\Pay;

use ReflectionClass;

/**
 * A fluent interface ({@link http://martinfowler.com/bliki/FluentInterface.html}) for creating transaction objects.
 *
 * @author Andreas Kollaros <andreas@larium.net>
 */
class TransactionBuilder
{
    private $transaction;

    private $amount;

    private $currency;

    private $transactionInstance;

    private $transactionId;

    private $extraOptions = [];

    private $description;

    private $clientIp;

    private $merchantReference;

    private $constructArgs = [];

    private $address = [];

    private $customerEmail;

    private static $saleMethods = [
        'currency',
        'description',
        'clientIp',
        'address',
        'customerEmail',
    ];

    private function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public static function purchase($amount)
    {
        $instance = new self('Larium\Pay\Transaction\PurchaseTransaction');
        $instance->constructArgs[] = $amount;

        return $instance;
    }

    public static function authorize($amount)
    {
        $instance = new self('Larium\Pay\Transaction\AuthorizeTransaction');
        $instance->constructArgs[] = $amount;

        return $instance;
    }

    public static function capture($amount)
    {
        $instance = new self('Larium\Pay\Transaction\CaptureTransaction');
        $instance->constructArgs[] = $amount;

        return $instance;
    }

    public static function refund($amount)
    {
        $instance = new self('Larium\Pay\Transaction\RefundTransaction');
        $instance->constructArgs[] = $amount;

        return $instance;
    }

    public static function void($transactionId)
    {
        $instance = new self('Larium\Pay\Transaction\VoidTransaction');
        $instance->constructArgs[] = $transactionId;

        return $instance;
    }

    public function charge(CardReference $card)
    {
        $this->constructArgs[] = $card;

        return $this;
    }

    public function with($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function withExtraOptions(array $extraOptions)
    {
        $this->extraOptions = $extraOptions;

        return $this;
    }

    public function describedAs($description)
    {
        $this->description = $description;

        return $this;
    }

    public function billTo(array $address)
    {
        $this->address = $address;

        return $this;
    }

    public function mailTo($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function withMerchantReference($merchantReference)
    {
        $this->merchantReference = $merchantReference;

        return $this;
    }

    public function withClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    public function getTransaction()
    {
        $this->build();

        return $this->transactionInstance;
    }

    private function build()
    {
        $reflection = new ReflectionClass($this->transaction);

        $args = $this->constructArgs;
        $args[] = $this->extraOptions;

        $this->transactionInstance = $reflection->newInstanceArgs($args);

        if ($this->transactionInstance instanceof Transaction\Sale) {
            foreach (self::$saleMethods as $prop) {
                $method = 'set' . (ucwords($prop));
                $this->transactionInstance->$method($this->$prop);
            }
        }
    }
}
