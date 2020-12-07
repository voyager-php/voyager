<?php

namespace Voyager\Http\Route;

use Closure;
use Voyager\Util\Arr;
use Voyager\Facade\Str;

class Route extends RouteBase
{
    /**
     * Store routes data.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $routes;

    /**
     * Store group settings.
     * 
     * @var \Voyager\Http\Route\Setting
     */

    private static $setting;

    /**
     * Store route group prefix.
     * 
     * @var string
     */

    private static $prefix;

    /**
     * Create new route instance.
     * 
     * @param   mixed $verb
     * @param   string $uri
     * @param   string $arg
     * @return  void
     */

    private function __construct($verb, string $uri, string $arg)
    {
        $this->setRouteData();

        if(!is_null(static::$setting))
        {
            foreach(static::$setting->data() as $key => $value)
            {
                if(!in_array($key, ['verb', 'uri']))
                {
                    if(array_key_exists($key, $this->middleware_keys) && !is_null($value) && $value)
                    {
                        $this->middlewares->push($this->middleware_keys[$key]);
                    }

                    $this->set($key, $value);
                }
                else if($key === 'verb')
                {
                    $this->verb($value);
                }
            }
        }

        if($uri !== '/')
        {
            $uri = Str::moveFromEnd($uri, '/');
        }

        if(!is_null(static::$prefix))
        {
            $uri = static::$prefix . $uri;
        }

        $this->verb($verb);
        $this->set('uri', $uri);

        if(Str::has($arg, '@'))
        {
            $this->set('controller', Str::break($arg, '@')[0]);
            $this->set('method', Str::break($arg, '@')[1]);
        }
        else
        {
            $this->set('method', $arg);
        }
    }

    /**
     * Route instance factory.
     * 
     * @param   mixed $verbs
     * @param   string $uri
     * @return  mixed $arg
     */

    public static function request($verbs, string $uri, $arg = 'index')
    {
        if($verbs === '*')
        {
            $verbs = ['get', 'post', 'put', 'patch', 'delete'];
        }

        return static::add(new self($verbs, $uri, $arg));
    }

    /**
     * Create new route that accept any methods.
     * 
     * @param   string $uri
     * @param   mixed $arg
     */

    public static function any(string $uri, $arg = 'index')
    {
        return static::request('*', $uri, $arg);
    }

    /**
     * Register new route instance.
     * 
     * @param   \Voyager\Http\Route
     * @return  \Voyager\Http\Route
     */

    private static function add(Route $instance)
    {
        if(is_null(static::$routes))
        {
            static::$routes = new Arr();
        }

        static::$routes->push($instance);

        return $instance;
    }

    /**
     * Create GET request route.
     * 
     * @param   string $uri
     * @param   mixed $arg
     * @return  $this
     */

    public static function get(string $uri, string $arg = 'index')
    {
        return static::add(new self('get', $uri, $arg));
    }

    /**
     * Create POST request route.
     * 
     * @param   string $uri
     * @param   string $arg
     * @return  $this
     */

    public static function post(string $uri, string $arg = 'index')
    {
        return static::add(new self('post', $uri, $arg));
    }

    /**
     * Create PUT request route.
     * 
     * @param   string $uri
     * @param   string $arg
     * @return  $this
     */

    public static function put(string $uri, string $arg = 'index')
    {
        return static::add(new self('put', $uri, $arg));
    }

    /**
     * Create PATCH request route.
     * 
     * @param   string $uri
     * @param   string $arg
     * @return  $this
     */

    public static function patch(string $uri, string $arg = 'index')
    {
        return static::add(new self('patch', $uri, $arg));
    }

    /**
     * Create DELETE request route.
     * 
     * @param   string $uri
     * @param   string $arg
     * @return  $this
     */

    public static function delete(string $uri, string $arg = 'index')
    {
        return static::add(new self('delete', $uri, $arg));
    }

    /**
     * Create new resource routes.
     * 
     * @param   string $prefix
     * @param   string $controller
     */

    public static function resource(string $prefix, string $controller)
    {
        $name = str_replace('/', '.', Str::moveFromBothEnds($prefix, '/'));
        $base = Str::moveFromEnd($prefix, '/') . '/';
        
        static::get($prefix, $controller . '@index')->name($name . '.index');
        static::get($base . 'create', $controller . '@create')->name($name . '.create');
        static::post($base . 'create', $controller . '@store')->name($name . '.store');
        static::get($base . '{id}', $controller . '@show')->name($name . '.show');
        static::put($base . '{id}', $controller . '@update')->name($name . '.update');
        static::patch($base . '{id}', $controller . '@update')->name($name . '.update');
        static::delete($base . '{id}', $controller . '@destroy')->name($name . '.destroy');
        static::get($base . '{id}/edit', $controller . '@edit')->name($name . '.edit');
    }

    /**
     * Register new route group.
     * 
     * @param   \Closure $closure
     * @return  void
     */

    public static function group(string $prefix, Closure $closure)
    {
        static::$prefix = $prefix;
        static::$setting = new Setting();
        
        $closure(static::$setting);

        static::$setting = null;
        static::$prefix = null;
    }

    /**
     * Return all registered routes.
     * 
     * @return  array
     */

    public static function all()
    {
        return !static::empty() ? static::$routes->get() : [];
    }

    /**
     * Return true if no registered routes.
     * 
     * @return void
     */

    public static function empty()
    {
        return !is_null(static::$routes) ? static::$routes->empty() : true;
    }

    /**
     * Clear registered routes.
     * 
     * @return void
     */

    public static function clear()
    {
        if(!static::empty())
        {
            static::$routes->truncate();
        }
    }

}