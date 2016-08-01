<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay;

class ParamBag
{
    private $params;

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
        return $this->offsetExists($name, $this->params);
    }

    /**
     * Returns an array from params.
     *
     * @access public
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->params;
    }

    /* -(  Iterator  )------------------------------------------------------ */

    public function rewind()
    {
        reset($this->params);
    }

    public function current()
    {
        return current($this->params);
    }

    public function key()
    {
        return key($this->params);
    }

    public function next()
    {
        next($this->params);
    }

    public function valid()
    {
        return current($this->params) !== false;
    }

    /* -(  ArrayAccess  )--------------------------------------------------- */

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->params[] = $value;
        } else {
            $this->params[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->params);
    }

    public function offsetUnset($offset)
    {
        unset($this->params[$offset]);
    }

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
