<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\Exception\UnableToCommitException;

trait Commit
{
    /**
     * @var bool
     */
    private $committed = false;

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if (!$this->canCommit()) {
            throw new UnableToCommitException();
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
            throw new \RuntimeException('Transaction is already committed.');
        }

        return true;
    }
}
