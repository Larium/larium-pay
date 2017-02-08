<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;

class InitialTransaction implements Initial
{
    use Commit;

    /**
     * @var int
     */
    private $amount = 0;

    /**
     * @var string
     */
    private $currency = '';

    /**
     * @var string
     */
    private $successUri;

    /**
     * @var string
     */
    private $cancelUri;

    /**
     * @var ParamsBag
     */
    private $extraOptions;

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
