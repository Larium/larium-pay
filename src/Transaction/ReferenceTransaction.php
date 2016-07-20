<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

abstract class ReferenceTransaction implements Transaction
{
    use Commit;

    private $id;

    private $amount;

    public function __construct($amount, $id)
    {
        $this->amount = $amount;
        $this->id = $id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getId()
    {
        return $this->id;
    }

    public function canCommit()
    {
        return $this->amount > 0
            && $this->id !== null;
    }
}
