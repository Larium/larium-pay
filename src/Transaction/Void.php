<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

interface Void extends Transaction
{
    public function getTransactionId();
}
