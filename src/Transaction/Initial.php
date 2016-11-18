<?php

namespace Larium\Pay\Transaction;

interface Initial extends Transaction
{
    public function getAmount();

    public function getCurrency();

    public function getSuccessUri();

    public function getCancelUri();
}
