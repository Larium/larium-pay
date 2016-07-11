<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

class Card implements CardReference
{
    private $name;

    private $number;

    private $month;

    private $year;

    private $cvv;

    private $token;

    public function __construct(array $card)
    {
        $defaults = [
            'name' => 'JOHN DOE',
            'number' => null,
            'month' => '01',
            'year' => '1970',
            'cvv' => null,
            'token' => null,
        ];

        $card = array_merge($defaults, $card);

        $this->name = $card['name'];
        $this->number = $card['number'];
        $this->month = $card['month'];
        $this->year = $card['year'];
        $this->cvv = $card['cvv'];
        $this->token = $card['token'];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getCvv()
    {
        return $this->cvv;
    }

    public function getToken()
    {
        return $this->token;
    }
}
