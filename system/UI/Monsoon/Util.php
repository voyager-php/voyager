<?php

namespace Voyager\UI\Monsoon;

use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\Str as Builder;

class Util
{
    /**
     * Store all registered utilities.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $utilities;

    /**
     * Store configurations.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private $config;

    /**
     * CSS utility name.
     * 
     * @var string
     */

    private $name;

    /**
     * Store css properties.
     * 
     * @var \Voyager\Util\Arr
     */

    private $props;

    /**
     * Store default values from config.
     * 
     * @var \Voyager\Util\Arr
     */

    private $default;

    /**
     * Store color for css values.
     * 
     * @var \Voyager\Util\Arr
     */

    private $color;

    /**
     * Store units for css values.
     * 
     * @var \Voyager\Util\Arr
     */

    private $units;

    /**
     * Store fallback values.
     * 
     * @var \Voyager\Util\Arr
     */

    private $instead;

    /**
     * Store properties which are important;
     */

    private $important;

    /**
     * Create new instance by constructor.
     * 
     * @param   string $name
     * @return  void
     */

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->props = new Arr();
        $this->default = new Arr();
        $this->units = new Arr();
        $this->instead = new Arr();
        $this->colors = new Arr();
        $this->important = new Arr();
        $this->config = new Collection(Config::get()['default']);
        static::$utilities->push($this);
    }

    /**
     * Return css util name.
     * 
     * @return string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set new css property.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function prop(string $key, $value, bool $important = false)
    {
        $this->props->set($key, $value);

        if($important)
        {
            $this->important->push($key);
        }

        return $this;
    }

    /**
     * Set new css property with variable value.
     * 
     * @param   string $key
     * @param   string $default
     * @param   string $instead
     * @param   bool $important
     * @return  $this
     */

    public function value(string $key, string $default, string $unit = null, string $instead = null, bool $important = false)
    {
        $this->default->set($key, $default);

        if(!is_null($instead))
        {
            $this->instead->set($key, $instead);
        }
        
        if(!is_null($unit))
        {
            $this->units->set($key, $unit);
        }
        
        if($important)
        {
            $this->important->push($key);
        }

        return $this;
    }

    /**
     * Apply utility classes for color.
     * 
     * @param   string $key
     * @return  $this
     */

    public function color(string $key, string $color = null, bool $important = false)
    {
        $this->colors->set($key, $color);

        if($important)
        {
            $this->important->push($key);
        }

        return $this;
    }

    /**
     * Generate css class utilities.
     * 
     * @param   string $val
     * @return  string
     */

    public function generate(string $val = null)
    {
        $str = new Builder('{');
        
        foreach($this->props->get() as $key => $value)
        {
            $str->append($key . ':' . $value);

            if($this->important->has($key))
            {
                $str->append(' !important');
            }
            
            $str->append(';');
        }

        foreach($this->default->get() as $key => $value)
        {
            $config = new Arr($this->config->{$value} ?? []);
            
            if(is_null($val) && $this->instead->hasKey($key))
            {
                $str->append($key . ':' . $this->instead->get($key));
            }
            else if($config->hasKey($val))
            {
                $str->append($key . ':' . $config->get($val));
            }
            else
            {
                if(is_null($val) && $this->instead->hasKey($key))
                {
                    $str->append($key . ':' . $this->instead->get($key));
                }   
                else
                {
                    $str->append($key . ':' . $val . ($this->units->hasKey($key) ? $this->units->get($key) : ''));
                }
            }

            if($this->important->has($key))
            {
                $str->append(' !important');
            }

            $str->append(';');
        }

        foreach($this->colors->get() as $key => $value)
        {
            $config = new Arr($this->config->colors ?? []);
            
            if($config->hasKey($value))
            {
                $color = $config->get($value);
                
                if(is_string($color))
                {
                    $str->append($key . ':' . $color);
                }
                else if(is_array($color))
                {
                    if(array_key_exists($val, $color))
                    {
                        $str->append($key . ':' . $color[$val]);
                    }
                }
            }
            else
            {
                $str->append($key . ':' . $val);
            }

            if($this->important->has($key))
            {
                $str->append(' !important');
            }

            $str->append(';');
        }

        $str->append('}');

        return $str->get();
    }

    /**
     * Create new instance of css utility.
     * 
     * @param   string $name
     * @return  $this
     */

    public static function set(string $name)
    {
        if(is_null(static::$utilities))
        {
            static::$utilities = new Arr();
        }

        return new self($name);
    }

    /**
     * Return all registered utilities.
     * 
     * @return  \Voyager\Util\Arr
     */

    public static function get()
    {
        return static::$utilities;
    }

}