<?php

declare(strict_types=1);

namespace Larium\Pay\Client;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class XmlClient extends AbstractClient
{
    private array $headers = [];

    public function __construct(
        private readonly string $uri,
        array $options = []
    ) {
        $this->options = $options;
    }

    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Post given xml string to remote gateway.
     *
     * @param string $xml
     * @return array @see self::resolveResponse
     */
    public function post(string $xml): array
    {
        $factory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $factory->createRequest('POST', $this->uri);
        foreach ($this->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        };

        $body = Psr17FactoryDiscovery::findStreamFactory()->createStream($xml);
        $request = $request->withBody($body);

        return $this->resolveResponse($this->sendRequest($request));
    }

    /**
     * Resolve the response from client.
     *
     * @param ResponseInterface $response
     * @return array An array with following values:
     *              'status'      : The Http status of response
     *              'headers'     : An array of response headers
     *              'body'        : The response string.
     *              'raw_response': The raw body response for logging purposes.
     *              'raw_request' : The raw body request for logging purposes.
     */
    protected function resolveResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->__toString();

        return [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $body,
            'raw_response' => $body,
            'raw_request' => $this->rawRequest,
        ];
    }

    protected function authenticate(RequestInterface $request): RequestInterface
    {
        return $request;
    }
}
