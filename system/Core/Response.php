<?php

namespace Voyager\Core;

use Closure;
use Voyager\Util\Data\Collection;
use Voyager\Util\Helper\Arr;

class Response
{
    private $output;
    private $valid = true;
    private $copy;
    private $data;

    public function __construct(array $data)
    {
        $this->copy = $data;
        $this->data = $data;
        
        // Test if the provided data is in right format.

        foreach($data as $item)
        {
            if(!($item instanceof Collection || is_array($item)))
            {
                $this->valid = false;
            }
        }
    }

    /**
     * DATA 
     * ----------------------------------
     * Return the original copy of provided
     * data.
     */

    public function data()
    {
        return $this->copy;
    }

    /**
     * HAS VALID DATA 
     * ----------------------------------
     * Return true if provided with valid
     * data.
     */

    public function hasValidData()
    {
        return $this->valid;
    }

    /**
     * ADD FIELD 
     * ----------------------------------
     * Dynamically create a new field in
     * each row of the data set.
     */

    public function add(string $key, Closure $closure)
    {
        $data = [];
        foreach($this->data as $item)
        {
            if($item instanceof Collection)
            {
                $array = Arr::make($item->toArray());
                if(!$array->hasKey($key))
                {
                    $array->set($key, $closure($item));
                }

                $data[] = new Collection($array->toArray());
            }
            else if(is_array($item))
            {
                $array = Arr::make($item);
                if(!$array->hasKey($key))
                {
                    $array->set($key, $closure($item));
                }

                $data[] = new Collection($array->toArray());
            }
        }
        $this->data = $data;
        return $this;
    }

    /**
     * RENAME FIELD 
     * ----------------------------------
     * Change the index key of fields.
     */

    public function rename(string $key, string $oldKey)
    {
        $data = [];
        foreach($this->data as $item)
        {
            if($item instanceof Collection)
            {
                $data[] = new Collection(Arr::make($item->toArray())->renameKey($key, $oldKey)->toArray());
            }
            else if(is_array($item))
            {
                $data[] = new Collection(Arr::make($item)->renameKey($key, $oldKey)->toArray());
            }
        }
        $this->data = $data;
        return $this;
    }

    /**
     * FORMAT VALUE 
     * ----------------------------------
     * Change value formatting like date
     * and time.
     */

    public function formatValue(string $key, Closure $arg)
    {
        $data = [];
        foreach($this->data as $item)
        {
            if($item instanceof Collection)
            {
                $array = Arr::make($item->toArray());
                if($array->hasKey($key))
                {
                    $array->set($key, $arg($array->get($key)));
                }
                $data[] = new Collection($array->toArray());
            }
            else if(is_array($item))
            {
                $array = Arr::make($item);
                if($array->hasKey($key))
                {
                    $array->set($key, $arg($array->get($key)));
                }
                $data[] = new Collection($array->toArray());
            }
        }
        $this->data = $data;
        return $this;
    }

    /**
     * FORMAT DATETIME VALUE 
     * ----------------------------------
     * Transform timestamp value to more
     * readable string.
     */

    public function formatDatetime(string $key, string $format = null)
    {
        $this->formatValue($key, function($datetime) use ($format) {
            if(!is_null($format))
            {
                return date($format, strtotime($datetime));
            }
            else
            {
                return date('F d, Y h:i:s', strtotime($datetime));
            }
        });
        return $this;
    }

    /**
     * REMOVE Field
     * ----------------------------------
     * Delete fields.
     */

    public function remove($key)
    {
        if(is_string($key))
        {
            $this->data = $this->deleteField($key);
        }
        else if(is_array($key))
        {
            foreach($key as $item)
            {
                $this->data = $this->deleteField($item);
            }
        }
        
        return $this;
    }

    /**
     * REMOVE FIELD 
     * ----------------------------------
     * Return data without the deleted
     * field.
     */

    private function deleteField(string $key)
    {
        $data = [];

        foreach($this->data as $item)
        {
            if($item instanceof Collection)
            {
                $data[] = new Collection(Arr::make($item->toArray())->deleteKey($key)->toArray());   
            }
            else if(is_array($item))
            {
                $data[] = new Collection(Arr::make($item)->deleteKey($key)->toArray());
            }
        }

        return $data;
    }

    /**
     * OUTPUT 
     * ----------------------------------
     * Return transformed output array.
     */

    public function output()
    {
        if(is_null($this->output))
        {
            $data = [];
            foreach($this->data as $item)
            {
                if($item instanceof Collection)
                {
                    $data[] = $item->toArray();
                }
                else if(is_array($item))
                {
                    $data[] = $item;
                }
            }

            $this->output = $data;
        }

        return $this->output;
    }

    /**
     * JSON
     * ----------------------------------
     * Return default json formatted output.
     */

    public function paginate()
    {
        return json($this->output(), true);
    }

}