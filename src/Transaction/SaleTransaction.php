<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;
use Larium\Pay\CardReference;
use Larium\Pay\Transaction\Sale;

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
     * @var CardReference
     */
    private $card;

    /**
     * @var array
     */
    private $address = [];

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
     * @var Larium\Pay\ParamsBag
     */
    private $extraOptions;

    public function __construct(
        $amount,
        CardReference $card,
        array $extraOptions = []
    ) {
        $this->amount = $amount;
        $this->card = $card;
        $this->extraOptions = new ParamsBag($extraOptions);
        $this->address = new ParamsBag();
    }

    /**
     * @return CardReference
     */
    public function getCardReference()
    {
        return $this->card;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Larium\Pay\ParamsBag
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
     * @return string
     */
    public function getMerchantReference()
    {
        return $this->merchantReference;
    }

    public function setMerchatReference($merchantReference)
    {
        $this->allowChanges();
        $this->merchantReference = $merchantReference;
    }

    /**
     * @return string
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
     * @return string
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
     * @return string
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
        return $this->card instanceof CardReference
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
