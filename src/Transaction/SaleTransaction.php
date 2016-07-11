<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

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

    public function __construct($amount, CardReference $card)
    {
        $this->amount = $amount;
        $this->card = $card;
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
     * @return array
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(array $address)
    {
        $this->allowChanges();
        $this->address = $address;
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
}
