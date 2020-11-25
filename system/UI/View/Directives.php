<?php

namespace Voyager\UI\View;

use Closure;
use Voyager\Util\File\Reader;

class Directives
{
    private static $keys = [];
    private static $registered = [];
    private static $declared = false;
    private $key;
    private $closure;

    private function __construct(string $key, Closure $closure)
    {
        $this->key = $key;
        $this->closure = $closure;
    }

    /**
     * CLOSURE
     * ----------------------------------
     * Return closure object.
     */

    public function closure()
    {
        return $this->closure;
    }

    /**
     * DECLARATIONS
     * ----------------------------------
     * Declare your directives here.
     */

    public static function declarations()
    {
        
    }

    /**
     * CALL
     * ----------------------------------
     * Call directive closures.
     */

    public static function call(string $key, string $value)
    {
        $closure = static::$registered[$key]->closure();
        return $closure($value);
    }

    /**
     * SET
     * ----------------------------------
     * Create new template directives.
     */

    public static function set(string $key, Closure $closure)
    {
        if(!array_key_exists($key, static::$registered))
        {
            static::$keys[] = $key;
            static::$registered[$key] = new self($key, $closure);
        }
    }

    /**
     * KEYS
     * ----------------------------------
     * Return registered directive keys.
     */

    public static function keys()
    {
        return static::$keys;
    }

    /**
     * GET
     * ----------------------------------
     * Return registered directives.
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