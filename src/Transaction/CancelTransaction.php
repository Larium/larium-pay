<?php

declare(strict_types=1);

namespace Larium\Pay\Transaction;

use Larium\Pay\ParamsBag;

class CancelTransaction implements Cancel
{
    use Commit;

    private $id;

    private $extraOptions;

    public function __construct($id, array $extraOptions = [])
    {
        $this->id = $id;
        $this->extraOptions = new ParamsBag($extraOptions);
    }

    public function getId()
    {
        return $this->id;
    }

    public function canCommit()
    {
        return $this->id !== null;
    }

    public function getExtraOptions()
    {
        return $this->extraOptions;
    }
}
