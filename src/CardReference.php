<?php

namespace Larium\Pay;

interface CardReference
{
    public function getName();

    public function getNumber();

    public function getMonth();

    public function getYear();

    public function getCvv();

    public function getToken();
}
