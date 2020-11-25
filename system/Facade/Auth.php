<?php

namespace Voyager\Facade;

class Auth extends Facade
{

    /**
     * Create a new facade instance.
     * 
     * @param   array $arguments
     * @return  mixed
     */

    protected static function construct(array $arguments)
    {
        return new self(\Voyager\Http\Security\Authentication::class);
    }

}