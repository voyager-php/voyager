<?php

namespace Voyager\Http\Validation;

use Voyager\Core\Config as Configuration;

class Config extends Configuration
{

    /**
     * Instantiate config instance.
     * 
     * @param   mixed $argument
     * @return  $this
     */

    protected static function bind()
    {
        return new self('validation', 'config/validation.php');
    }

    /**
     * Return validation data.
     * 
     * @param   mixed $data
     * @return  array
     */

    protected static function send($data)
    {
        $list = [];

        foreach(Param::get() as $key => $item)
        {
            $list[$key] = $item->data();
        }

        return $list;
    }

}