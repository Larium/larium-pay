<?php

namespace Larium\Pay\Transaction;

interface Reference
{
    public function getId();

    public function getAmount();
}
