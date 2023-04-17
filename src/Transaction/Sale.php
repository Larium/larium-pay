<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Sale extends Transaction
{
    /**
     * @return \Larium\CreditCard\CreditCard
     */
    public function getCard();

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * Address should have the following keys:
     * <code>
     *      $address['name']
     *      $address['company']
     *      $address['address1']
     *      $address['address2']
     *      $address['city']
     *      $address['state']
     *      $address['country']
     *      $address['zip']
     *      $address['phone']
     * </code>
     *
     * @return \Larium\Pay\ParamsBag
     */
    public function getAddress();

    /**
     * @return string
     */
    public function getMerchantReference();

    /**
     * @return string
     */
    public function getClientIp();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getCustomerEmail();

    /**
     * @return \Larium\Pay\ParamsBag
     */
    public function getExtraOptions();
}
