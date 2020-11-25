<?php

namespace Voyager\Database;

use Voyager\Util\Arr;

abstract class DataType
{
    /**
     * Column arguments.
     * 
     * @var mixed
     */

    protected $argument;

    /**
     * Store data type.
     */

    protected $data;

    /**
     * Store column key.
     * 
     * @return  string
     */

    protected $key;

    /**
     * Create new instance.
     * 
     * @param   string $key
     * @param   mixed $argument
     * @return  void
     */

    public function __construct(string $key, $argument = null)
    {
        $this->key = $key;
        $this->data = new Arr();
        $this->argument = $argument;
        $this->setArgument();
    }

    /**
     * Set argument data to the data array.
     * 
     * @return  void
     */

    protected function setArgument() {}

    /**
     * Generate column SQL. 
     * 
     * @return  void
     */

    protected function generate() {}

    /**
     * Return data values.
     * 
     * @param   string $name
     * @return  mixed
     */

    public function __get(string $name)
    {
        if($this->data->hasKey($name))
        {
            return $this->data->get($name);
        }
    }

    /**
     * Set data value.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    protected function set(string $key, $value)
    {
        $this->data->set($key, $value);

        return $this;
    }

    /**
     * Do not let field empty.
     * 
     * @return $this
     */

    public function notNull()
    {
        return $this->set('not_null', true);
    }

}