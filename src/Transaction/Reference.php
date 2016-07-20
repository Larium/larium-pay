<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

interface Reference
{
    public function getId();

    public function getAmount();
}
