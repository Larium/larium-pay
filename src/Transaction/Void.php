<?php

namespace Larium\Pay\Transaction;

interface Void extends Transaction
{
    public function getId();
}
