<?php
/**
 *
 * Base iterator for Declaration and Selector lists.
 *
 */
namespace CssCrush;

class Iterator implements \IteratorAggregate, \ArrayAccess, \Countable
{
    public $store;

    public function __construct($items = [])
    {
        $this->store = $items;
    }

    /*
        IteratorAggregate implementation.
    */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->store);
    }

    /*
        ArrayAccess implementation.
    */
    #[\ReturnTypeWillChange]
    public function offsetExists($index)
    {
        return array_key_exists($index, $this->store);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($index)
    {
        return isset($this->store[$index]) ? $this->store[$index] : null;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($index, $value)
    {
        $this->store[$index] = $value;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($index)
    {
        unset($this->store[$index]);
    }

    public function getContents()
    {
        return $this->store;
    }

    /*
        Countable implementation.
    */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->store);
    }

    /*
        Collection interface.
    */
    public function filter($filterer, $op = '===')
    {
        $collection = new Collection($this->store);
        return $collection->filter($filterer, $op);
    }
}
