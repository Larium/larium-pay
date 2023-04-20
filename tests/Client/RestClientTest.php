<?php

declare(strict_types=1);

namespace Larium\Pay\Client;

use Http\Mock\Client;
use Laminas\Diactoros\ResponseFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RestClientTest extends TestCase
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

    public function testGetRequest(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $client = $this->getMockRestClient($response, '');

        $client->get('1', 'limit=1');
    }

    public function testPostRequest(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $client = $this->getMockRestClient($response, 'payments');

        $client->addHeader('User-Agent', 'Larium Pay Library');

        $client->post(['amount' => 100]);
    }

    public function testPutRequest(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $client = $this->getMockRestClient($response, 'payments');

        $client->put('1', ['amount' => 100]);
    }

    public function testDeleteRequest(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $client = $this->getMockRestClient($response, 'payments');

        $client->delete('1');
    }

    public function testBasicAuthenticationRequest(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $client = $this->getMockRestClient($response, 'payments', function (RequestInterface $request) {
            $auth = $request->getHeaderLine('Authorization');
            if (empty($auth)) {
                return false;
            }

            if (!hash_equals($auth, 'Basic dXNlcm5hbWU6cGFzc3dvcmQ=')) {
                return false;
            }

            return true;
        });

        $client->setBasicAuthentication('username', 'password');

        $client->post([]);
    }

    public function testAuthenticationHeaderRequest(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $client = $this->getMockRestClient($response, 'payments', function (RequestInterface $request) {
            $auth = $request->getHeaderLine('Authorization');
            if (empty($auth)) {
                return false;
            }

            if (!hash_equals($auth, '123')) {
                return false;
            }

            return true;
        });

        $client->setHeaderAuthentication('Authorization', '123');

        $client->post([]);
    }

    private function getMockRestClient(
        ResponseInterface $response,
        string $resource = '',
        callable $fun = null
    ): RestClient|MockObject {
        /** @var RestClient|MockObject $mock */
        $mock =  $this->getMockBuilder(RestClient::class)
            ->onlyMethods(['discoverClient'])
            ->setConstructorArgs(['example.com', $resource])
            ->getMock();
        $mock->expects($this->once())
            ->method('discoverClient')
            ->willReturn($this->getMockClient($response, $fun));

        return $mock;
    }

    private function getMockClient(ResponseInterface $response, callable $fun = null): ClientInterface
    {
        /** @var ClientInterface|MockObject */
        $client = $this->createMock(ClientInterface::class);

        $exp = $client->expects($this->once())->method('sendRequest');
        if ($fun !== null) {
            $exp->with(self::callback($fun));
        }
        $exp->willReturn($response);

        return $client;
    }
}
