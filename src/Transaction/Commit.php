<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\TransactionException;

trait Commit
{
    /**
     * @var bool
     */
    private $committed = false;

    abstract public function canCommit();

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if (!$this->canCommit()) {
            throw TransactionExeption::unableToCommit();
        }
        $this->committed = true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCommitted()
    {
        return $this->committed;
    }

    /**
     * {@inheritdoc}
     */
    public function allowChanges()
    {
        if ($this->isCommitted()) {
            throw TransactionException::alreadyCommited();
        }

        return true;
    }
}
