<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

use Larium\Pay\Exception\UnableToCommitException;

trait Commit
{
    private $committed = false;

    public function commit()
    {
        if (!$this->canCommit()) {
            throw new UnableToCommitException();
        }
        $this->committed = true;
    }

    public function isCommitted()
    {
        return $this->committed;
    }

    public function allowChanges()
    {
        if ($this->isCommitted()) {
            throw new \RuntimeException('Transaction is already committed.');
        }

        return true;
    }
}
