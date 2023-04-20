<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Transfer extends Transaction
{
    /**
     * @return int
     */
    public function getAmount();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * The unique string that will identify the recipient to transfer money.
     * It can be an email address, an IBAN etc.
     *
     * @return string
     */
    public function getRecipientIdentifier();
}
