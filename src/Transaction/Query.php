<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Query extends Transaction
{
    /**
     * Get an array of criteria to pass to remote gateway.
     *
     * @return array
     */
    public function getCriteria();
}
