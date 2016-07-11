<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

class RestClientTest extends \PHPUnit_Framework_TestCase
{
    public function testResourceUri()
    {
        $baseUri = 'https://api.example.com';
        $resource = 'payments';

        $client = new RestClient($baseUri, $resource);

        $uri = $client->getUri();
        $this->assertEquals('https://api.example.com/payments', $uri);

        $uri = $client->getUri('1');
        $this->assertEquals('https://api.example.com/payments/1', $uri);
    }
}
