<?php

namespace Voyager\Util\Data;

use Voyager\Util\Arr;
class Collection
{
    /**
     * Store collection data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $data;

    /**
     * Instantiate new collection object.
     * 
     * @param   mixed $data
     * @return  void
     */

    public function __construct($data = [])
    {
        if($data instanceof Arr)
        {
            $data = $data->get();
        }

        $this->data = new Arr($data);
    }

    /**
     * Override all collection data.
     * 
     * @param   mixed $data
     * @return  $this
     */

    public function override($data)
    {
        if($data instanceof Arr)
        {
            $data = $data->get();
        }

        foreach($data as $key => $item)
        {
            if($this->has($key))
            {
                $this->data->set($key, $item);
            }
            else
            {
                $this->add($key, $item);
            }
        }

        return $this;
    }

    /**
     * Check if collection has key.
     * 
     * @param   string $key
     * @return  bool
     */

    public function has(string $key)
    {
        return $this->data->hasKey($key);
    }

    /**
     * Return key by value.
     * 
     * @param   mixed $value
     * @return  string
     */

    public function key($value)
    {
        return $this->data->indexOf($value);
    }

    /**
     * Add new collection data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function add(string $key, $value)
    {
        if(!$this->has($key))
        {
            if($value instanceof Arr)
            {
                $value = $value->get();
            }
            else if($value instanceof Collection)
            {
                $value = $value->toArray();
            }

            $this->data->set($key, $value);
        }
        
        return $this;
    }

    /**
     * Remove collection data.
     * 
     * @param   string $key
     * @return  $this
     */

    public function remove(string $key)
    {
        $this->data->remove($key);

        return $this;
    }

    /**
     * Return collection data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function __get(string $key)
    {
        if($this->has($key))
        {
            return $this->data->get($key);
        }
    }

    /**
     * Prevent overriding of data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  void
     */

    public function __set(string $key, $value) {}

    /**
     * Return data as array.
     * 
     * @return array
     */

    public function toArray()
    {
        return $this->data->get();
    }

    /**
     * Return json encode data.
     * 
     * @return  string
     */

    public function toJson()
    {
        return $this->data->toJson();
    }

    /**
     * Return collection keys.
     * 
     * @return  array
     */

    public function keys()
    {
        return $this->data->keys();
    }

    /**
     * Return true if collection is empty.
     * 
     * @return  bool
     */

    public function empty()
    {
        return $this->data->empty();
    }

}