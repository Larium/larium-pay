<?php

namespace Larium\Pay\Transaction;

class ThreedSecureAuthenticateTransaction implements ThreedSecureAuthenticate
{
    use Commit;

    private $pares;

    private $transactionId;

    public function __construct($pares, $transactionId)
    {
        $this->pares = $pares;
        $this->transactionId = $transactionId;
    }

    public function getPares()
    {
        return $this->pares;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function canCommit()
    {
        return $this->pares
            && $this->transactionId;
    }
}
