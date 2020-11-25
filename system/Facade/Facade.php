<?php

namespace Voyager\Facade;

abstract class Facade
{
    /**
     * Prevent calling this methods in facade.
     * 
     * @var array
     */

    protected $exclude = [];

    /**
     * Facade instance object.
     * 
     * @var $this
     */

    protected $instance;

    /**
     * Instantiate facade instance.
     * 
     * @param   mixed
     * @return  void
     */

    protected function __construct()
    {
        $arguments = func_get_args();
        $class = $arguments[0];

        array_shift($arguments);

        $this->instance = new $class(...$arguments);
    }

    /**
     * Return facade object instance.
     * 
     * @return  mixed
     */

    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Return all excluded methods.
     */

    public function exclude()
    {
        return $this->exclude;
    }

    /**
     * Call non static method statically.
     * 
     * @param   string $name
     * @param   array $arguments
     */

    public static function __callStatic(string $name, array $arguments)
    {
        $instance = static::construct($arguments);
        
        if(!in_array($name, $instance->exclude()) && method_exists($instance->getInstance(), $name))
        {
            return static::callback($instance->getInstance()->{$name}(...static::argument($arguments)));
        }

        return $instance->getInstance();
    }

    /**
     * Override by the parent class.
     * 
     * @param   array $arguments
     * @return  array
     */

    protected static function argument(array $arguments)
    {
        return $arguments;
    }

    /**
     * Override by the parent class.
     * 
     * @param   mixed $instance
     * @return  mixed
     */

    protected static function callback($instance)
    {
        return $instance;
    }

}