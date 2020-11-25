<?php

namespace Voyager\Facade;

use Voyager\Util\Arr;
use Voyager\Util\Str as Builder;

class Str extends Facade
{
    /**
     * Prevent calling this methods in facade.
     * 
     * @var array
     */

    protected $exclude = [
        'get',
        'set',
    ];

    /**
     * Create a new facade instance.
     * 
     * @param   array $arguments
     * @return  mixed
     */

    protected static function construct(array $arguments)
    {
        return new self(\Voyager\Util\Str::class, $arguments[0]);
    }

    /**
     * Return the arguments to be provided while calling the method.
     * 
     * @param   array $arguments
     * @return  mixed
     */

    protected static function argument(array $arguments)
    {
        array_shift($arguments);
        return $arguments;
    }

    /**
     * Before returning the response, do some more.
     * 
     * @param   mixed $response
     * @return  mixed
     */

    protected static function callback($response)
    {
        return is_a($response, 'Voyager\Util\Str') ? $response->get() : $response;
    }

    /**
     * Create new Str instance.
     * 
     * @param   string $string
     * @return  \Voyager\Util\Str
     */

    public static function make(string $string = '')
    {
        return new Builder($string);
    }

    /**
     * Generate random string.
     * 
     * @param   int $length
     * @return  string
     */

    public static function random(int $length)
    {
        $characters = new Builder('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $builder = new Builder();
        $split = new Arr($characters->split());
        $max = (int)$split->length() - 1;

        for($i = 1; $i <= $length; $i++)
        {
            $builder->append($split->get(rand(0, $max)));
        }

        return $builder->get();
    }

    /**
     * Convert string to kebab case.
     * 
     * @param   string $word
     * @return  string
     */

    public static function toKebabCase(string $word)
    {
        $builder = new Builder();
        $first = false;

        foreach(str_split($word) as $char)
        {
            if(strtoupper($char) === $char && $first)
            {
                $builder->append('_')->append($char)->toLower();
            }
            else
            {
                $builder->append($char)->toLower();
            }

            $first = true;
        }

        return $builder->get();
    }

    /**
     * Convert string to snake case.
     */

    public static function toSnakeCase(string $word)
    {
        $builder = new Builder();
        $first = false;



        return $builder->get();
    }

}