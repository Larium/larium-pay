<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;

class TransferTransaction implements Transfer
{
    use Commit;

    private $amount = 0;

    private $currency = '';

    private $recipientIdentifier;

    private $extraOptions;

    public function __construct(
        $amount,
        $currency,
        $recipientIdentifier,
        array $extraOptions = []
    ) {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->recipientIdentifier = $recipientIdentifier;
        $this->extraOptions = new ParamsBag($extraOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipientIdentifier()
    {
        return $this->recipientIdentifier;
    }

    public function canCommit()
    {
        return $this->amount > 0
            && !empty($this->recipientIdentifier);
    }

    public function getExtraOptions()
    {
        return $this->extraOptions;
    }
}
