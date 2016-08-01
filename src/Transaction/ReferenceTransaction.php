<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;

abstract class ReferenceTransaction implements Transaction
{
    use Commit;

    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $amount = 0;

    /**
     * @var Larium\Pay\ParamsBag
     */
    private $extraOptions;

    public function __construct($amount, $id, array $extraOptions = [])
    {
        $this->id = $id;
        $this->amount = $amount;
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
