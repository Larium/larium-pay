<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Reference
{
    public function getId();

    public function getAmount();
}
