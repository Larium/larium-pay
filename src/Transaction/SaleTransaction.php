<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;
use Larium\Pay\Transaction\Sale;
use Larium\CreditCard\CreditCard;

abstract class SaleTransaction implements Sale
{
    use Commit;

    /**
     * @var int
     */
    private $amount = 0;

    /**
     * @var string
     */
    private $currency = '';

    /**
     * @var CreditCard
     */
    private $card;

    /**
     * @var ParamsBag
     */
    private $address;

    /**
     * @var string
     */
    private $merchantReference;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $customerEmail;

    /**
     * @var ParamsBag
     */
    private $extraOptions;

    public function __construct(
        $amount,
        CreditCard $card,
        array $extraOptions = []
    ) {
        $this->amount = $amount;
        $this->card = $card;
        $this->extraOptions = new ParamsBag($extraOptions);
        $this->address = new ParamsBag();
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(array $address)
    {
        $this->allowChanges();
        $this->address = new ParamsBag($address);
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchantReference()
    {
        return $this->merchantReference;
    }

    public function setMerchantReference($merchantReference)
    {
        $this->allowChanges();
        $this->merchantReference = $merchantReference;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    public function setClientIp($clientIp)
    {
        $this->allowChanges();
        $this->clientIp = $clientIp;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->allowChanges();
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail($customerEmail)
    {
        $this->allowChanges();
        $this->customerEmail = $customerEmail;
    }

    public function canCommit()
    {
        return $this->card instanceof CreditCard
            && $this->amount > 0;
    }

    public function getExtraOptions()
    {
        return $this->extraOptions;
    }

    public function setCurrency($currency)
    {
        $this->allowChanges();
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}
