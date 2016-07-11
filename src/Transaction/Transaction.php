<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

interface Transaction
{
    /**
     * Lock current transaction and decline further changes.
     * If transaction state is invalid, an UnableToCommitException should be
     * thrown.
     *
     * @throws UnableToCommitException
     *
     * @return void
     */
    public function commit();

    /**
     * Checks if transaction can be committed.
     *
     * @return bool
     */
    public function canCommit();

    public function isCommitted();

    /**
     * Checks if further changes allowed for this transaction.
     *
     * @throws RuntimeException
     *
     * @return bool
     */
    public function allowChanges();
}
