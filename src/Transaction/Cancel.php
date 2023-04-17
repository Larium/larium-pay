<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Cancel extends Transaction
{
    public function getId();
}
