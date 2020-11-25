<?php

namespace Voyager\Util\Http;

use Voyager\Facade\Str;
use Voyager\Util\Arr;

class Session
{
    /**
     * If session has started.
     * 
     * @var bool
     */

    private static $started = false;

    /**
     * Hashing algorithm to use.
     * 
     * @var string
     */

    private $algo = 'md5';

    /**
     * Session id.
     * 
     * @var string
     */

    private $id;

    /**
     * Store session data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $data;

    /**
     * Create new instance of session.
     * 
     * @param   string $job
     * @param   string $id
     * @param   array $data
     * @return  void
     */
    
    public function __construct(string $id, array $data = null)
    {
        if(session_id() === '' && !static::$started)
        {
            static::$started = true;
            session_start();
        }

        $this->id = $this->hash($id);
        $this->data = new Arr();

        if(!$this->exist())
        {
            $_SESSION[$this->id] = [
                'data' => [],
                'copy' => [],
            ];

            if(!is_null($data))
            {
                foreach($data as $key => $value)
                {
                    $this->set($key, $value);
                }
            }
        }
        else
        {
            $this->data->set($_SESSION[$this->id]['data']);
        }
    }

    /**
     * Hash session keys.
     * 
     * @param   string $key
     * @return  string
     */

    private function hash(string $key)
    {
        return Str::hash($key, $this->algo);
    }

    /**
     * Return true if session exist.
     * 
     * @return  bool
     */

    public function exist()
    {
        return array_key_exists($this->id, $_SESSION);
    }

    /**
     * Check if session key exist.
     * 
     * @param   string $key
     * @return  bool
     */

    public function has(string $key)
    {
        return $this->exist() && $this->data->hasKey($this->hash($key));
    }

    /**
     * Return session data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function get(string $key)
    {
        if($this->has($key))
        {
            return $this->data->get($this->hash($key));
        }
    }

    /**
     * Set session data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function set(string $key, $value)
    {
        $hash = $this->hash($key);
        
        if($this->get($key) !== $value || !$this->has($key))
        {
            $_SESSION[$this->id]['data'][$hash] = $value;
        }

        if(!$this->has($key))
        {
            $_SESSION[$this->id]['copy'][$hash] = $value;
        }

        $this->data->set($hash, $value);

        return $this;
    }

    /**
     * Delete session data.
     * 
     * @param   string $key
     * @return  $this
     */

    public function remove(string $key)
    {
        if($this->has($key))
        {
            $hash = $this->hash($key);

            $this->data->remove($hash);
            unset($_SESSION[$this->id]['data'][$hash]);
        }

        return $this;
    }

    /**
     * Delete the entire session.
     * 
     * @return  void
     */

    public function delete()
    {
        if($this->exist())
        {
            $this->data->truncate();
            unset($_SESSION[$this->id]);
        }
    }

    /**
     * Reset session data.
     * 
     * @return $this
     */

    public function revert()
    {
        $this->data->set($_SESSION[$this->id]['copy']);
        $_SESSION[$this->id]['data'] = $this->data->get();

        return $this;
    }

    /**
     * Empty the session.
     * 
     * @return $this
     */

    public function truncate()
    {
        $this->data->truncate();
        $_SESSION[$this->id]['data'] = [];
        $_SESSION[$this->id]['copy'] = [];

        return $this;
    }

}