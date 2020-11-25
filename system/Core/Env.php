<?php

namespace Voyager\Core;

use Voyager\Facade\Str;
use Voyager\Util\Arr;

class Env extends Config
{
    /**
     * Instantiate config instance.
     * 
     * @param   mixed $argument
     * @return  $this
     */

    protected static function bind()
    {
        return new self('env', '.env');
    }

    /**
     * What kind of data to return?
     * 
     * @param   mixed $data
     * @return  mixed
     */

    protected static function send($data)
    {
        $env = new Arr();
        
        foreach($data as $line)
        {
            $line = Str::moveFromStart(Str::remove($line, ["\n", "\r"]), ' ');
            
            if(!Str::startWith($line, '#') && !Str::empty($line))
            {
                $break = Str::break($line, '=');
                $name  = Str::toUpper($break[0]);
                $value = Str::moveFromEnd($break[1], ' ');

                if(!Str::empty($name))
                {
                    if(strtolower($value) === 'null')
                    {
                        $env->setNull($name, 'null');
                    }
                    else if(strtolower($value) === 'false')
                    {
                        $env->set($name, false);
                    }
                    else if(strtolower($value) === 'true')
                    {
                        $env->set($name, true);
                    }
                    else if(is_numeric($value) || is_int($value))
                    {
                        $env->set($name, (int)$value);
                    }
                    else
                    {
                        $env->set($name, (string)$value);
                    }
                }
            }
        }

        if(function_exists('app'))
        {
            app()->cache = $env->has('APP_CACHE') ? $env->get('APP_CACHE') : false;
        }

        return $env->get();
    }

}