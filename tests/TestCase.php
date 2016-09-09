<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

use Larium\Pay\Card;
use Larium\Pay\Response;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getFixture($gateway)
    {
        $ini = parse_ini_file(__DIR__ . "/fixtures.ini", true);

        $data = new \ArrayIterator($ini);

        return $data[$gateway];
    }

    protected function assertSuccess(Response $response)
    {
        $this->assertTrue($response->isSuccess());
    }

    protected function assertFailure(Response $response)
    {
        $this->assertFalse($response->isSuccess());
    }

    protected function getCard()
    {
        return new Card([
            'name' => 'JOHN DOE',
            'number' => '4111111111111111',
            'month' => '01',
            'year' => date('Y') + 1,
            'cvv' => '123',
        ]);
    }
}
