<?php

namespace Voyager\Resource\Locale;

use Voyager\Resource\Locale\Translations;
use Voyager\Util\Arr;

class Locale extends Translations
{
    /**
     * Store locale data.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $locales;

    /**
     * Store locale data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $data;

    /**
     * Store translation data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $translations;

    /**
     * Locale instance id.
     * 
     * @var string
     */

    private $id;

    /**
     * Create new instance of locale class.
     * 
     * @param   string $id
     * @return  void
     */

    private function __construct(string $id) {

        if(is_null(static::$locales))
        {
            static::$locales = new Arr();
        }

        $this->data = new Arr();
        $this->translations = new Arr($this->languages);
        $this->id = $id;
    }

    /**
     * Return locale id.
     * 
     * @return  string
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set translation text.
     * 
     * @param   string $key
     * @param   array $arg
     * @return  $this
     */

    public function __call(string $key, array $arg)
    {
        if($this->translations->hasKey($key) && !$this->data->hasKey($key))
        {
            $this->data->set($key, $arg[0]);

            return $this;
        }
    }

    /**
     * Return translation data array.
     * 
     * @return  \Voyager\Util\Arr
     */

    public function data()
    {
        return $this->data->get();
    }

    /**
     * Create new instance of locale class.
     * 
     * @return  \Voyager\Resource\Locale\Locale
     */

    public static function id(string $id)
    {
        $instance = new self($id);
        static::$locales->push($instance);

        return $instance;
    }

    /**
     * Return registered locale instances.
     * 
     * @return \Voyager\Util\Arr
     */

    public static function get()
    {
        return static::$locales->get();
    }

    /**
     * Return true if no registered locale.
     * 
     * @return  bool
     */

    public static function empty()
    {
        return static::$locales->empty();
    }

    /**
     * Clear registered locale instances.
     * 
     * @return  void
     */

    public static function clear()
    {
        static::$locales->truncate();
    }

}