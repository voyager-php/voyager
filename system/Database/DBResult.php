<?php

namespace Voyager\Database;

use Voyager\Util\Data\Bundle;

class DBResult extends DBResponse
{
    /**
     * Store result in bundle objects.
     * 
     * @var \Voyager\Util\Data\Bundle
     */

    private $bundle;

    /**
     * Return result bundle.
     * 
     * @return  \Voyager\Util\Data\Bundle
     */

    public function fetch()
    {
        if(is_null($this->bundle))
        {
            $this->bundle = new Bundle($this->conn->fetch());
            $this->conn->freeResult();
        }

        return $this->bundle;
    }

    /**
     * Return the very first result.
     * 
     * @return  mixed
     */

    public function first()
    {
        $this->fetch();
        return $this->bundle->first();
    }

    /**
     * Return the very last result.
     * 
     * @return  mixed
     */

    public function last()
    {
        $this->fetch();
        return $this->bundle->last();
    }

    /**
     * Return row by index number.
     * 
     * @param   int $index
     * @return  mixed
     */

    public function get(int $index)
    {
        if($index <= $this->numRows())
        {
            $this->fetch();
            return $this->bundle->get($index);
        }
    }

    /**
     * Return total number of rows.
     * 
     * @return  int
     */

    public function numRows()
    {
        $this->fetch();
        return (int)$this->bundle->size();
    }

    /**
     * Return true if result has now rows.
     * 
     * @return  bool
     */

    public function empty()
    {
        return $this->numRows() === 0;
    }

    /**
     * Return result as array.
     * 
     * @return  array
     */

    public function toArray()
    {
        $this->fetch();
        return $this->bundle->toArray();
    }

    /**
     * Return json encoded result.
     * 
     * @return  string
     */

    public function toJson()
    {
        $this->fetch();
        return $this->bundle->toJson();
    }

    /**
     * Transform database result.
     * 
     * @param   string $class
     * @return  array
     */

    public function transform(string $class)
    {
        return $this->fetch()->transform($class);
    }

    /**
     * If result is just one row, automatically get
     * values by calling it as property.
     * 
     * @param   string $name
     * @return  mixed
     */

    public function __get(string $name)
    {
        if($this->numRows() === 1)
        {
            $data = $this->first();

            if($data->has($name))
            {
                return $data->{$name};
            }
        }
    }

}