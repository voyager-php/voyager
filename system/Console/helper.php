<?php

    use Voyager\Core\Env;
    use Voyager\Util\Arr;

    /**
     * Set the root path.
     * 
     * @param   string $path
     * @return  string
     */

    if(!function_exists('path'))
    {
        function path(string $path)
        {
            return $path;
        }
    }

    /**
     * Return .env properties.
     * 
     * @param   string $key
     * @param   mixed $default
     * @return  mixed
     */

    if(!function_exists('env'))
    {
        function env(string $key, $default = null)
        {
            $env = new Arr(Env::get());

            return $env->hasKey($key) ? $env->get($key) : $default;
        }
    }