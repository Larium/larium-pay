<?php

declare(strict_types=1);

namespace Larium\Pay\Client;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RestClient extends AbstractClient
{
    private string $username = '';

    private string $pass = '';

    private array $headerAuthentication = [];

    public function __construct(
        private readonly string $baseUri,
        private readonly string $resource,
        private array $headers = [],
        array $options = []
    ) {
        $this->options = $options;
    }

    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function get(string $id = null, string|array $payload = ''): array
    {
        $uri = $this->getUri($id);
        if ($query = $this->normalizePayload($payload)) {
            $uri = $uri . '?' . ltrim($query, '?');
        }

        return $this->request($uri, 'GET');
    }

    public function post(string|array $payload): array
    {
        return $this->request($this->getUri(), 'POST', $payload);
    }

    public function put(string $id, string|array $payload = ''): array
    {
        $uri = $this->getUri($id);

        return $this->request($uri, 'PUT', $payload);
    }

    private function request(
        string $uri,
        string $method,
        string|array $payload = ''
    ): array {
        $factory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $factory->createRequest(
            $method,
            $uri
        );

        foreach ($this->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if (is_array($payload)) {
            $payload = $this->normalizePayload($payload);
        }

        if (!empty($payload)) {
            $stream = Psr17FactoryDiscovery::findStreamFactory()->createStream($payload);
            $request = $request->withBody($stream);
        }

        $request = $this->authenticate($request);

        return $this->resolveResponse($this->sendRequest($request));
    }

    public function delete(string $id): array
    {
        $uri = $this->getUri($id);

        return $this->request($uri, 'DELETE');
    }

    public function getUri(string $id = null): string
    {
        $uri = $this->resource
            ? sprintf('%s/%s', $this->baseUri, $this->resource)
            : $this->baseUri;

        if ($id) {
            $uri = sprintf($uri, $id);
        }

        return $uri;
    }

    public function setBasicAuthentication(
        string $username,
        string $password
    ): void {
        $this->username = $username;
        $this->pass = $password;
    }

    public function setHeaderAuthentication(string $name, string $value): void
    {
        $this->headerAuthentication = ['name' => $name, 'value' => $value];
    }

    protected function authenticate(RequestInterface $request): RequestInterface
    {
        if (!empty($this->username) || !empty($this->pass)) {
            $authentication = new BasicAuth($this->username, $this->pass);

            return $authentication->authenticate($request);
        }

        if (!empty($this->headerAuthentication)) {
            $request = $request->withHeader(
                $this->headerAuthentication['name'],
                $this->headerAuthentication['value']
            );

            return $request;
        }

        return $request;
    }

    private function normalizePayload(string|array $payload): string
    {
        if (is_string($payload)) {
            return $payload;
        }

        return http_build_query($payload);
    }

    /**
     * Resolve the response from client.
     *
     * @param ResponseInterface $response
     * @return array An array with following values:
     *              'status': The Http status of response
     *              'headers': An array of response headers
     *              'body': The json decoded body response. (Since we are in
     *              RestClient)
     *              'raw_response': The raw body response for logging purposes.
     *              'raw_request': The raw body request for logging purposes.
     */
    protected function resolveResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->__toString();
        $responseBody = json_decode($body, true) ?: [];

        return [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $responseBody,
            'raw_response' => $body,
            'raw_request' => $this->rawRequest,
        ];
    }
}
