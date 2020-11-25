<?php

namespace Voyager\Http\Security;

use Voyager\Core\Config as Configuration;

class AuthConfig extends Configuration
{
    /**
     * Instantiate config instance.
     * 
     * @param   mixed $argument
     * @return  $this
     */

    protected static function bind()
    {
        return new self('authentication', 'config/authentication.php');
    }

}