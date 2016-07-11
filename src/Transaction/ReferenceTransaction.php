<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

abstract class ReferenceTransaction implements Transaction
{
    use Commit;

    private $transactionId;

    private $amount;

    public function __construct($amount, $transactionId)
    {
        $this->amount = $amount;
        $this->transactionId = $transactionId;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function canCommit()
    {
        return $this->amount > 0
            && $this->transactionId !== null;
    }
}
