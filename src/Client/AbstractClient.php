<?php

declare(strict_types=1);

namespace Larium\Pay\Client;

use Larium\Http\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractClient
{
    protected string $rawRequest = '';

    protected array $options = [];

    abstract protected function authenticate(RequestInterface $request): RequestInterface;

    abstract public function addHeader(string $name, string $value): void;

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $this->authenticate($request);

        $response = $this->discoverClient()->sendRequest($request);

        if ($request->getBody()->isSeekable()) {
            $request->getBody()->rewind();
        }
        $this->rawRequest = $request->getBody()->__toString();

        return $response;
    }

    protected function discoverClient(): ClientInterface
    {
        $client = new Client($this->options);

        return $client;
    }
}
