<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;

class InitialTransaction implements Initial
{
    use Commit;

    private $amount = 0;

    private $currency = 0;

    private $successUri;

    private $cancelUri;

    private $extraOptions = [];

    public function __construct(
        $amount,
        $successUri,
        $cancelUri,
        array $extraOptions = []
    ) {
        $this->amount = $amount;
        $this->cancelUri = $cancelUri;
        $this->successUri = $successUri;
        $this->extraOptions = new ParamsBag($extraOptions);
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getSuccessUri()
    {
        return $this->successUri;
    }

    public function getCancelUri()
    {
        return $this->cancelUri;
    }

    public function canCommit()
    {
        return 4 === count(
            array_filter([
                $this->amount,
                $this->currency,
                $this->successUri,
                $this->cancelUri,
            ], 'strlen')
        );
    }

    public function getExtraOptions()
    {
        return $this->extraOptions;
    }

    public function setCurrency($currency)
    {
        $this->allowChanges();
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}
