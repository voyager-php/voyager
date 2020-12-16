<?php

namespace Voyager\Core;

use Voyager\Facade\Str;
use Voyager\Util\File\Reader;

class Env
{
    /**
     * Env file cache key.
     * 
     * @var string
     */

    private static $key = 'env';

    /**
     * Load and return .env data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public static function get(string $key = null)
    {
        $cache = function_exists('cache') ? cache(static::$key) : null;

        if(is_null($cache))
        {
            $file = new Reader('.env');

            if($file->exist())
            {
                $data = static::parse($file->lines());

                if(function_exists('app'))
                {
                    app()->cache = array_key_exists('APP_CACHE', $data) ? $data['APP_CACHE'] : false;
                }

                $cache = function_exists('cache') ? cache(static::$key, $data) : $data;
            }
        }

        if(!is_null($key) && array_key_exists($key, $cache))
        {
            return $cache[$key];
        }
        else
        {
            return $cache;
        }
    }

    /**
     * Parse the application properties.
     * 
     * @param   array $data
     * @return  mixed
     */

    private static function parse(array $data)
    {
        $env = [];
        
        foreach($data as $line)
        {
            $line = Str::moveFromStart(Str::remove($line, ["\n", "\r"]), ' ');
            
            if(!Str::startWith($line, '#') && !Str::empty($line))
            {
                $break = Str::break($line, '=');
                $name  = strtoupper($break[0]);
                $value = Str::moveFromEnd($break[1], ' ');

                if(!Str::empty($name))
                {
                    if(strtolower($value) === 'null')
                    {
                        $env[$name] = null;
                    }
                    else if(strtolower($value) === 'false')
                    {
                        $env[$name] = false;
                    }
                    else if(strtolower($value) === 'true')
                    {
                        $env[$name] = true;
                    }
                    else if(is_numeric($value) || is_int($value))
                    {
                        $env[$name] = (int)$value;
                    }
                    else
                    {
                        $env[$name] = (string)$value;
                    }
                }
            }
        }

        return $env;
    }

}