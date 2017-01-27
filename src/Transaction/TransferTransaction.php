<?php

namespace Larium\Pay\Transaction;

class TransferTransaction implements Transfer
{
    use Commit;

    private $amount = 0;

    private $currency = '';

    private $recipientIdentifier;

    public function __construct($amount, $currency, $recipientIdentifier)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->recipientIdentifier = $recipientIdentifier;
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
}
