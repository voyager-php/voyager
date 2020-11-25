<?php

namespace Voyager\Http\Validation;

use Voyager\Util\Arr;

abstract class ValidateBase
{
    /**
     * Parameters used to validate values.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $data;

    /**
     * Initiate parameter data.
     * 
     * @return  void
     */

    protected function init()
    {
        $this->data = new Arr([
            'name'                      => null,
            'optional'                  => false,
            'numeric'                   => false,
            'min_length'                => 1,
            'max_length'                => null,
            'min_word'                  => 1,
            'max_word'                  => null,
            'min_value'                 => null,
            'max_value'                 => null,
            'min_line'                  => 1,
            'max_line'                  => null,
            'min_letters'               => null,
            'max_letters'               => null,
            'min_numbers'               => null,
            'max_numbers'               => null,
            'min_lowercase'             => null,
            'max_lowercase'             => null,
            'min_uppercase'             => null,
            'max_uppercase'             => null,
            'min_nonalphanumeric'       => null,
            'max_nonalphanumeric'       => null,
            'start_with'                => [],
            'end_with'                  => [],
            'contains'                  => [],
            'not_contain'               => [],
            'must_not'                  => [],
            'expect'                    => [],
            'regex_pattern'             => null,
        ]);
    }

    /**
     * Return parameter data in array.
     * 
     * @return  array
     */

    public function data()
    {
        return $this->data->get();
    }

    /**
     * Set parameter data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  void
     */

    protected function set(string $key, $value)
    {
        if($this->data->hasKey($key))
        {
            $this->data->set($key, $value);
        }

        return $this;
    }

    /**
     * Dynamically return parameter data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function __get(string $key)
    {
        if($this->data->hasKey($key))
        {
            return $this->data->get($key);
        }
    }

}