<?php

namespace Voyager\Core;

use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\File\Reader;

abstract class Config
{
    /**
     * Store config data.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $data;

    /**
     * Cache key.
     * 
     * @var string
     */

    private $key;

    /**
     * File location.
     * 
     * @var string
     */

    private $file;

    /**
     * Store cache object.
     * 
     * @var \Voyager\Core\Cache
     */

    private $cache;

    /**
     * Create new config data.
     * 
     * @param   string $file
     * @param   mixed $data
     * @return  void
     */

    public function __construct(string $key, string $file)
    {
        $this->key              = $key;
        $this->file             = $file;
        $this->cache            = new Cache($key);
    }

    /**
     * Return config cache key.
     * 
     * @return string
     */

    public function key()
    {
        return $this->key;
    }

    /**
     * Load the config data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function read()
    {
        if($this->cache->exist())
        {
            return cache($this->key);
        }
        else
        {
            $file = new Reader($this->file);

            if($file->exist())
            {
                if(!static::$data->hasKey($this->key))
                {
                    if($file->is('php'))
                    {
                        $data = static::send($file->require());
                        static::$data->set($this->key, $data);

                        return $data;
                    }
                }
                else
                {
                    return static::$data->get($this->key);
                }
            }
        }
    }

    /**
     * Get and cache config data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public static function get(string $key = null)
    {
        if(is_null(static::$data))
        {
            static::$data = new Arr();
        }

        $bind = static::bind();
        $data = $bind->read();
        
        if(function_exists('app'))
        {
            cache($bind->key(), $data);
        }

        if(!is_null($key))
        {
            if($data instanceof Arr)
            {
                return $data->get($key);
            }
            else if($data instanceof Collection)
            {
                return $data->{$key};
            }
            else if(is_array($data) && array_key_exists($key, $data))
            {
                return $data[$key];
            }
            else
            {
                return null;
            }
        }

        return $data;
    }

    /**
     * Override from the parent class.
     * 
     * @return  $this
     */

    protected static function bind() {}

    /**
     * Override from the parent class.
     * 
     * @param   mixed $data
     * @return  mixed
     */

    protected static function send($data) {
        return $data;
    }

}