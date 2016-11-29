<?php

namespace Larium\Pay\Transaction;

class QueryTransaction implements Query
{
    use Commit;

    private $criteria = [];

    public function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function canCommit()
    {
        return true;
    }
}
