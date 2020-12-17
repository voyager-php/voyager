<?php

namespace Voyager\App;

use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;

class Parameter
{
    /**
     * Store parameter value.
     * 
     * @var mixed
     */

    private $value;

    /**
     * Store request parameter key.
     * 
     * @var string
     */

    private $key;

    /**
     * Parameter validation properties.
     * 
     * @var \Voyager\Util\Arr
     */

    private $data;

    /**
     * Create new parameter instance.
     * 
     * @param   string $name
     * @param   string $value
     */

    public function __construct(?string $value, string $key)
    {
        $this->data = new Arr([
            'type'          => null,
            'optional'      => false,
            'method'        => null,
            'default'       => null,
            'error'         => null,
            'messages'      => new Arr(),
        ]);

        $this->value = $value;
        $this->key = $key;
    }

    /**
     * Return parameter data as collection object.
     * 
     * @return  \Voyager\Util\Data\Collection
     */

    public function data()
    {
        return new Collection($this->data->get());
    }

    /**
     * Method names define the parameter keys.
     * 
     * @param   string $type
     * @return  $this
     */

    public function setType(string $type)
    {
        $this->data->set('type', $type);

        return $this;
    }

    /**
     * Set parameter request method.
     * 
     * @param   string $type
     * @return  $this
     */

    public function setMethod(string $method)
    {
        if(Str::equal($method, ['get', 'post']))
        {
            if($method === 'get')
            {
                $this->value = get($this->key);
            }
            else if($method === 'post')
            {
                $this->value = post($this->key);
            }

            $this->data->set('method', $method);
        }

        return $this;
    }

    /**
     * Set parameter validation to optional.
     * 
     * @param   bool $optional
     * @return  $this
     */

    public function setAsOptional(bool $optional = true)
    {
        $this->data->set('optional', $optional);

        return $this;
    }

    /**
     * Return parameter value.
     * 
     * @return  string
     */

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set default value in case the parameter value is null.
     * 
     * @param   string $value
     * @return  $this
     */

    public function setDefaultValue(string $value)
    {
        if(is_null($this->value) || empty($this->value))
        {
            $this->value = $value;
            $this->data->set('default', $value);
        }

        return $this;
    }

    /**
     * Set error message.
     * 
     * @param   string $message
     * @return  $this
     */

    public function setErrorMessage(string $message, int $code = null)
    {
        if(!is_null($code))
        {
            $messages = $this->data->get('messages');
            $messages->set('code_' . $code, $message);
        }
        else
        {
            if(is_null($this->data->get('error')))
            {
                $this->data->set('error', $message);
            }
        }

        return $this;
    }

    /**
     * Return error message by code.
     * 
     * @param   int $code
     * @return  string
     */

    public function getErrorMessage(int $code)
    {
        return $this->data->get('messages')->get('code_' . $code);
    }

}