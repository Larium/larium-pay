<?php

namespace Larium\Pay\Transaction;

class RetrieveTransaction implements Retrieve
{
    use Commit;

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function canCommit()
    {
        return null !== $this->id;
    }
}
