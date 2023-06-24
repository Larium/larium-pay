<?php

declare(strict_types=1);

namespace Larium\Pay;

use ArrayAccess;
use Iterator;

class ParamsBag implements Iterator, ArrayAccess
{
    /**
     * The array of option values.
     *
     * @var array
     */
    private $params = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function get(mixed $name, mixed $default = null): mixed
    {
        return $this->offsetExists($name)
            ? $this->offsetGet($name)
            : $default;
    }

    public function __get(mixed $name): mixed
    {
        return $this->offsetGet($name);
    }

    public function __set(mixed $name, mixed $value): void
    {
        $this->offsetSet($name, $value);
    }

    public function __isset($name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * Returns an array from params.
     *
     * @return array
     */
    public function getArrayCopy(): array
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        reset($this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        return current($this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function key(): mixed
    {
        return key($this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        next($this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return current($this->params) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->params[] = $value;
        } else {
            $this->params[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->params[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        $value = isset($this->params[$offset])
            ? $this->params[$offset]
            : null;

        if (is_array($value)) {
            return new self($value);
        }

        return $value;
    }
}
