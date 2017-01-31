<?php

namespace Larium\Pay;

use LogicException;

class TransactionException extends LogicException
{
    public static function unableToCommit()
    {
        return new self("Transaction cannot be commited.");
    }

    public static function alreadyCommited()
    {
        return new self('Transaction already commited. No changes allowed.');
    }
}
