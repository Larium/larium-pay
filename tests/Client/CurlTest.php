<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateConnection()
    {
        $payload = ['amoount' => 100, 'description' => 'test'];
        $conn = new Curl(
            'http://www.httpbin.org/post',
            Curl::METHOD_POST,
            $payload,
            [
                'Accept: application/json'
            ]
        );

        $response = $conn->execute();

        $this->assertEquals(200, $response['status']);
    }
}
