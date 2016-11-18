<?php

namespace Larium\Pay\Transaction;

interface Retrieve extends Transaction
{
    public function getid();
}
