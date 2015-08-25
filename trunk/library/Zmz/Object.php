<?php

/**
 * Zmz
 *
 * LICENSE
 *
 * This source file is subject to the GNU GPLv3 license that is bundled
 * with this package in the file COPYNG.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @copyright  Copyright (c) 2010-2011 Massimo Zappino (http://www.zappino.it)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html     GNU GPLv3 License
 */
class Zmz_Object implements ArrayAccess, Countable, IteratorAggregate
{

    /**
     * If set to true Exception will be thrown when attribute not found
     *
     * @var boolean
     */
    protected $_throwException;
    /**
     * Data structure where attributes will be stored
     *
     * @var stdClass
     */
    protected $data;


    public function __construct($values = null, $throwException = true)
    {

        $this->data = $this->resetAttributes();
        $this->setThrowException($throwException);
        if (is_array($values)) {
            $this->setFromArray($values);
        }
    }

    public function __get($attribute)
    {
        if (!isset($this->data->$attribute)) {
            if ($this->_throwException) {
                throw new Zmz_Exception('Attribute ' . $attribute . ' is not set');
            } else {
                return null;
            }
        }

        return $this->data->$attribute;
    }

    public function __set($key, $value)
    {
        $this->data->$key = $value;
    }

    public function __isset($key)
    {
        return isset($this->data->$key);
    }

    public function getData()
    {
        $attributes = $this->data;

        if ($attributes == null) {
            $attributes = array();
        }

        return $attributes;
    }

    public function resetAttributes()
    {
        $this->data = new stdClass();
    }

    public function setFromArray(array $array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $v = new self($v, $this->getThrowException());
            }
            $this->data->$k = $v;
        }

        return $this;
    }

    public function setThrowException($bool)
    {
        $this->_throwException = (bool) $bool;

        return $this;
    }

    public function toArray()
    {
        $attributes = $this->getData();
        $array = array();
        foreach ($attributes as $k => $v) {
            if ($v instanceof self) {
                $array[$k] = $v->toArray();
            } else {
                $array[$k] = $v;
            }
        }

        return $array;
    }

    /**
     *
     * @return boolean
     */
    public function getThrowException()
    {
        return $this->_throwException;
    }

    public function offsetExists($offset)
    {
        return isset($this->data->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->data->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->data->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data->$offset);
    }

    public function count()
    {
        return count($this->toArray());
    }

    public function getIterator()
    {
        return $this->getData();
    }

}
