<?php

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
    }

    public function testResourceUriWithId()
    {
        $baseUri = 'https://api.example.com';
        $resource = 'payments/%s/refund';

        $client = new RestClient($baseUri, $resource);

        $uri = $client->getUri('1');
        $this->assertEquals('https://api.example.com/payments/1/refund', $uri);

        $resource = 'payments/capture/%s';
        $client = new RestClient($baseUri, $resource);

        $uri = $client->getUri('1');
        $this->assertEquals('https://api.example.com/payments/capture/1', $uri);
    }
}
