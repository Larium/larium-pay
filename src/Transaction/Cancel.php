<?php

namespace Larium\Pay\Transaction;

interface Cancel extends Transaction
{
    public function getId();
}
