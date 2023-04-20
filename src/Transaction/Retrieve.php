<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Retrieve extends Transaction
{
    public function getId();
}
