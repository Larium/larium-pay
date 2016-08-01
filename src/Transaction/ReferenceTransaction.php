<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;

abstract class ReferenceTransaction implements Transaction
{
    use Commit;

    private $id;

    private $amount;

    /**
     * @var Larium\Pay\ParamsBag
     */
    private $extraOptions;

    public function __construct($amount, $id, array $extraOptions = [])
    {
        $this->amount = $amount;
        $this->id = $id;
        $this->extraOptions = new ParamsBag($extraOptions);
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

    public function getExtraOptions()
    {
        return $this->extraOptions;
    }
}
