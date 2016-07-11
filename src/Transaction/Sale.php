<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

use Larium\Pay\CardReference;

interface Sale extends Transaction
{
    /**
     * @return CardReference
     */
    public function getCardReference();

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @return array
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
}
