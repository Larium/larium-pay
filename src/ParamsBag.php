<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

use Iterator;
use ArrayAccess;

class ParamsBag implements Iterator, ArrayAccess
{
    /**
     * The array of option values.
     *
     * @var array
     */
    private $params = array();

    public function __construct(array $params = array())
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
    public function rewind()
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
    public function next()
    {
        next($this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return current($this->params) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
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
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
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
