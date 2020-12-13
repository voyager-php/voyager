<?php

namespace Voyager\Util;

use Closure;
use Voyager\Util\Data\Collection;

class Arr
{
    /**
     * Array data.
     * 
     * @var array
     */

    protected $array = [];

    /**
     * Create a new array instance.
     * 
     * @param   array $array
     * @return  void
     */

    public function __construct(array $array = [])
    {
        $this->set($array);
    }

    /**
     * Get array data.
     * 
     * @return  mixed
     */

    public function get($key = null)
    {
        if(!is_null($key))
        {
            if($this->hasKey($key))
            {
                return $this->array[$key];
            }
        }
        else
        {
            return $this->array;
        }
    }

    /**
     * Set array data.
     * 
     * @param   mixed $array
     * @param   mixed $value
     * @return  $this
     */

    public function set($arg, $value = null)
    {
        if(is_array($arg))
        {
            $this->array = $arg;
        }
        else if(is_string($arg) && !is_null($value))
        {
            $this->array[$arg] = $value;
        }
        else if($arg instanceof Arr)
        {
            $this->array = $arg->get();
        }

        return $this;
    }

    /**
     * Set null array property.
     * 
     * @param   string $key
     * @return  $this
     */

    public function setNull(string $key)
    {
        $this->array[$key] = null;

        return $this;
    }

    /**
     * Return the length of the array.
     * 
     * @return  $this
     */

    public function length()
    {
        return (int)sizeof($this->get());
    }

    /**
     * Return true if empty.
     * 
     * @return  $this
     */

    public function empty()
    {
        return $this->length() === 0;
    }

    /**
     * Return true if array is multidimensional.
     * 
     * @return  $this
     */

    public function isMultidimension()
    {
        return count($this->get()) !== count($this->get(), COUNT_RECURSIVE);
    }

    /**
     * Merge array.
     * 
     * @param   array $merge
     * @return  mixed
     */

    public function merge($merge)
    {
        if($merge instanceof Arr)
        {
            $merge = $merge->get();
        }

        return $this->set(array_merge($this->get(), $merge));
    }

    /**
     * Return all array keys.
     * 
     * @return  array
     */

    public function keys()
    {
        return array_keys($this->get());
    }

    /**
     * Return the array difference.
     * 
     * @param   array $array
     * @return  array
     */

    public function diff(array $array)
    {
        return array_diff($this->get(), $array);
    }

    /**
     * Check if array has index key.
     * 
     * @param   mixed $key
     * @return  bool
     */

    public function hasKey($key)
    {
        return in_array($key, $this->keys());
    }

    /**
     * Check if value exist from the array.
     * 
     * @param   mixed $value
     * @return  bool
     */

    public function has($value)
    {
        return in_array($value, $this->get());
    }

    /**
     * Find and return element key.
     * 
     * @param   mixed $value
     * @return  mixed
     */

    public function indexOf($value)
    {
        return array_search($value, $this->get());
    }

    /**
     * Return the first element from array.
     * 
     * @return  mixed
     */

    public function first()
    {
        return $this->get(0);
    }

    /**
     * Return the last element from array.
     * 
     * @return  mixed
     */

    public function last()
    {
        return $this->get($this->length() - 1);
    }

    /**
     * Add new value in the array.
     * 
     * @param   mixed $value
     * @return  $this
     */

    public function push($value)
    {
        $array = $this->get();
        array_push($array, $value);

        return $this->set($array);
    }

    /**
     * Remove the last element from array.
     * 
     * @return $this
     */

    public function pop()
    {
        $array = $this->get();
        array_pop($array);
        return $this->set($array);
    }

    /**
     * Push new value in front of array stack.
     * 
     * @param   mixed $value
     * @return  $this
     */

    public function unshift($value)
    {
        $array = $this->get();
        array_unshift($array, $value);

        return $this->set($array);
    }

    /**
     * Remove the first element from array.
     * 
     * @return $this
     */

    public function shift()
    {
        return $this->set(array_shift($this->get()));
    }

    /**
     * Remove array element.
     * 
     * @param   mixed $key
     * @return  $this
     */

    public function remove($key)
    {
        if($this->hasKey($key))
        {
            unset($this->array[$key]);
        }

        return $this;
    }

    /**
     * Remove set of values from array.
     * 
     * @param   mixed $value
     * @return  $this
     */

    public function removeValues($values)
    {
        if(is_string($values))
        {
            $values = explode(',', $values);
        }

        return $this->set($this->diff($values));
    }

    /**
     * Remove duplicate values.
     * 
     * @return $this
     */

    public function unique()
    {
        return $this->set(array_unique($this->get()));
    }

    /**
     * Return all values from the array.
     * 
     * @return  array.
     */

    public function values()
    {
        return array_values($this->get());
    }

    /**
     * Sort the array randomly.
     * 
     * @return $this
     */

    public function random()
    {
        $array = $this->get();
        shuffle($array);

        return $this->set($array);
    }

    /**
     * Reverse the order of the array.
     * 
     * @return $this
     */

    public function reverse()
    {
        return $this->set(array_reverse($this->get()));
    }

    /**
     * Convert array to json string.
     * 
     * @return  string
     */

    public function toJson()
    {
        return json_encode($this->get());
    }

    /**
     * Convert array to collection object.
     * 
     * @return \Voyager\Util\Data\Collection
     */

    public function toCollection()
    {
        return new Collection($this->get());
    }

    /**
     * Join the array values.
     * 
     * @param   string $glue
     * @return  string
     */

    public function implode(string $glue)
    {
        return implode($glue, $this->get());
    }

    /**
     * Join the array values.
     * 
     * @return  string
     */

    public function join()
    {
        return $this->implode('');
    }

    /**
     * Iterate in each array item.
     * 
     * @param   \Closure $callback
     * @return  void
     */

    public function each(Closure $callback)
    {
        foreach($this->get() as $key => $value)
        {
            $callback($key, $value);
        }
    }

    /**
     * Remove all elements from array.
     * 
     * @return $this
     */

    public function truncate()
    {
        return $this->set([]);
    }

}