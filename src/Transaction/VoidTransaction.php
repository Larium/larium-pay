<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

class VoidTransaction implements Void
{
    use Commit;

    private $transactionId;

    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function canCommit()
    {
        return $this->transactionId !== null;
    }
}
