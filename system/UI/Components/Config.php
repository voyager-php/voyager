<?php

namespace Voyager\UI\Components;

use Voyager\Core\Config as BaseConfig;

class Config extends BaseConfig
{

    /**
     * Instantiate config instance.
     * 
     * @param   mixed $argument
     * @return  $this
     */

    protected static function bind()
    {
        return new self('components', 'config/components.php');
    }

}