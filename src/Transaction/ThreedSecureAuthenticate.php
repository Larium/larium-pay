<?php

namespace Larium\Pay\Transaction;

interface ThreedSecureAuthenticate extends Transaction
{
    public function getPares();

    public function getTransactionId();
}
