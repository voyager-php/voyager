<?php

namespace Voyager\UI\View;

use Closure;

class Directives
{
    /**
     * Store directive keys.
     * 
     * @var array
     */

    private static $keys = [];

    /**
     * Store registered directives.
     * 
     * @var array
     */

    private static $registered = [];

    /**
     * Check if directives are declared.
     * 
     * @var bool
     */

    private static $declared = false;

    /**
     * Store directives closure.
     * 
     * @var \Closure
     */

    private $closure;

    /**
     * Instantiate directives class.
     * 
     * @param   \Closure $closure
     * @return  void
     */

    private function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * Return closure object.
     * 
     * @return  \Closure
     */

    public function closure()
    {
        return $this->closure;
    }

    /**
     * Declare your directives here.
     * 
     * @return  void
     */

    public static function declarations()
    {
        static::set('csrf_token', function($val) {
            return csrf_token();
        });
    }

    /**
     * Call directive closures.
     * 
     * @param   string $key
     * @param   string $value
     * @return  mixed
     */

    public static function call(string $key, string $value)
    {
        $closure = static::$registered[$key]->closure();

        return $closure($value);
    }

    /**
     * Create new template directives.
     * 
     * @param   string $key
     * @param   \Closure $closure
     * @return  void
     */

    private static function set(string $key, Closure $closure)
    {
        if(!array_key_exists($key, static::$registered))
        {
            static::$keys[] = $key;
            static::$registered[$key] = new self($closure);
        }
    }

    /**
     * Return registered directive keys.
     * 
     * @return  array
     */

    public static function keys()
    {
        return static::$keys;
    }

    /**
     * Return registered directives.
     * 
     * @return  array
     */

    public static function get()
    {
        if(!static::$declared)
        {
            static::declarations();
            static::$declared = true;
        }

        return static::$registered;
    }
}