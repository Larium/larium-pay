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

    public function get($name, $default = null)
    {
        return $this->offsetExists($name)
            ? $this->offsetGet($name)
            : $default;
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Returns an array from params.
     *
     * @return array
     */
    public function getArrayCopy()
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
    public function current()
    {
        return current($this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
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
    public function offsetGet($offset)
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
