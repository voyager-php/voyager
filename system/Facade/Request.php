<?php

namespace Voyager\Facade;

class Request extends Facade
{

    /**
     * Create a new instance of class.
     * 
     * @param   array $arguments
     * @return  mixed
     */

    protected static function construct(array $arguments)
    {
        return new self(\Voyager\Http\Request::class);
    }

}