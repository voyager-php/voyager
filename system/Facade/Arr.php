<?php

namespace Voyager\Facade;

class Arr extends Facade
{
    /**
     * Prevent calling this methods in facade.
     * 
     * @var array
     */

    protected $exclude = [
        'set',
    ];

    /**
     * Create a new instance of class.
     * 
     * @param   array $arguments
     * @return  mixed
     */

    protected static function construct(array $arguments)
    {
       return new self(\Voyager\Util\Arr::class, $arguments[0]);
    }

    /**
     * Return the arguments to be provided while calling the method.
     * 
     * @param   array $arguments
     * @return  mixed
     */

    protected static function argument(array $arguments)
    {
        array_shift($arguments);
        return $arguments;
    }
    
    /**
     * Before returning the response, do some more.
     * 
     * @param   mixed $response
     * @return  mixed
     */

    protected static function callback($response)
    {
        return is_a($response, 'Voyager\Util\Arr') ? $response->get() : $response;
    }

}