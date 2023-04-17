<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

interface Transaction
{
    /**
     * Lock current transaction and decline further changes.
     * If transaction state is invalid, an TransactionException should be
     * thrown.
     *
     * @throws Larium\Pay\TransactionException
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

    /**
     * Checks if transaction is committed or not.
     *
     * @return bool
     */
    public function isCommitted();

    /**
     * Checks if further changes allowed for this transaction, meaning that it is
     * not committed yet.
     *
     * @throws RuntimeException
     *
     * @return bool
     */
    public function allowChanges();
}
