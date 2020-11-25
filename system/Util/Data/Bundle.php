<?php

namespace Voyager\Util\Data;

use Closure;
use Voyager\Util\Arr;

class Bundle
{
    /**
     * Store bundle collections?
     * 
     * @var \Voyager\Util\Arr
     */

    private $collections;

    /**
     * Create new instance of bundle object.
     * 
     * @param   array $data
     * @return  void
     */
    
    public function __construct(array $data)
    {
        $this->collections = new Arr();
        $data = new Arr($data);
        
        if($data->isMultidimension() && !$data->empty())
        {
            $collections = new Arr();

            foreach($data->get() as $item)
            {
                if(is_array($item))
                {
                    $collections->push(new Collection($item));
                }
                else if($item instanceof Collection)
                {
                    $collections->push($item);
                }
            }
            
            $this->collections->set($collections);
        }
    }

    /**
     * Return number of rows the bundle has.
     * 
     * @return  int
     */

    public function size()
    {
        return $this->collections->length();
    }

    /**
     * Return true if bundle has no rows.
     * 
     * @return  bool
     */

    public function empty()
    {
        return $this->collections->empty();
    }

    /**
     * Return bundle data.
     * 
     * @return  bool
     */

    public function data()
    {
        return $this->collections->get();
    }

    /**
     * Return bundle data by index.
     * 
     * @param   int $index
     * @return  mixed
     */

    public function get(int $index)
    {
        return $this->collections->get($index);
    }

    /**
     * Return the very first bundle data.
     * 
     * @return  mixed
     */

    public function first()
    {
        return $this->collections->first();
    }

    /**
     * Return the last bundle data.
     * 
     * @return mixed
     */

    public function last()
    {
        return $this->collections->last();
    }

    /**
     * Iterate closure in each array value.
     * 
     * @param   \Closure
     * @return  void
     */

    public function each(Closure $callback)
    {
        $this->collections->each($callback);
    }

    /**
     * Return bundle data.
     * 
     * @return  array
     */

    public function toArray()
    {
        $array = new Arr();

        foreach($this->collections->get() as $collection)
        {
            $array->push($collection->toArray());
        }

        return $array->get();
    }

    /**
     * Convert bundle to json string format.
     * 
     * @return  string
     */

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Transform bundle data using transformer.
     * 
     * @param   string $class
     * @return  $this
     */

    public function transform(string $class)
    {
        $transformer = new $class($this->collections->get());
        $this->collections = new Arr($transformer->output());
        
        return $this;
    }

}