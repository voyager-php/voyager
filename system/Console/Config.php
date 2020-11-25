<?php

namespace Voyager\Console;

use Voyager\Core\Config as ConfigBase;

class Config extends ConfigBase
{

    /**
     * Instantiate config instance.
     * 
     * @param   mixed $argument
     * @return  $this
     */

    protected static function bind()
    {
        return new self('console', 'config/console.php');
    }

}