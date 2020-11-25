<?php

namespace Voyager\Util\Http;

use Voyager\Facade\Str;

class Cookie
{
    /**
     * Name of the cookie.
     * 
     * @var string
     */

    private $name;

    /**
     * Value of the cookie.
     * 
     * @var mixed
     */

    private $value;

    /**
     * Expiration of the cookie.
     * 
     * @var int
     */

    private $expire;

    /**
     * Create new instance of cookie class.
     * 
     * @param   string $name
     * @param   mixed $value
     * @return  void
     */

    public function __construct(string $name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;

        if(!$this->exist())
        {
            setcookie($this->hash(), $value);
        }
        else
        {
            if(is_null($value))
            {
                $this->value = $_COOKIE[$this->hash()];
            }
        }
    }

    /**
     * Return the name of the cookie.
     * 
     * @return  string
     */

    public function name()
    {
        return $this->name;
    }

    /**
     * Return hashed cookie name.
     * 
     * @return  string
     */

    private function hash()
    {
        return Str::hash($this->name);
    }

    /**
     * Return true if cookie exist.
     * 
     * @return  bool
     */

    public function exist()
    {
        return array_key_exists($this->hash(), $_COOKIE);
    }

    /**
     * Return or set cookie value.
     * 
     * @param   string $value
     * @return  mixed
     */

    public function set($value = null)
    {
        setcookie($this->hash(), $value, $this->expire);
        $this->value = $value;

        return $this;
    }

    /**
     * Return cookie value.
     * 
     * @return  mixed
     */

    public function value()
    {
        return $this->value;
    }

    /**
     * Set expiration timestamp of cookie.
     * 
     * @param   int $hours
     * @return  $this
     */

    public function expire(int $hours)
    {
        $this->expire = (time() + 3600) * $hours;
        setcookie($this->hash(), $this->value, $this->expire);

        return $this;
    }

    /**
     * Set cookie expiration to delete.
     * 
     * @return  void
     */

    public function delete()
    {
        setcookie($this->hash(), null, time()-3600);
        $this->value = null;
        $this->name = null;
        $this->expire = null;
    }

}