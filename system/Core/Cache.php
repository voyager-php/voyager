<?php

namespace Voyager\Core;

use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\File\Reader;

class Cache
{
    /**
     * Cache collection data.
     * 
     * @var \Voyager\Util\Arr
     */

    protected static $collection;

    /**
     * Store the cache data key.
     * 
     * @var string
     */

    protected $key;

    /**
     * Store cache data.
     * 
     * @var mixed
     */

    protected $data;

    /**
     * Store file reader object.
     * 
     * @var \Voyager\Util\File\Reader
     */

    protected $reader;

    /**
     * Path of the cache file.
     * 
     * @var string
     */

    private $path = 'storage/cache/';

    /**
     * Hashed cache filename.
     * 
     * @var string
     */

    private $hash = 'your_cached_data';

    /**
     * Cache data file extension.
     * 
     * @var string
     */

    private $ext = '.json';

    /**
     * Instantiate new cache data.
     * 
     * @param   string $key
     * @param   mixed $data
     * @return  void
     */

    public function __construct(string $key)
    {
        $this->key = Str::hash($key);
        $this->reader = new Reader($this->filename());

        if(is_null(static::$collection))
        {
            static::$collection = new Arr();
            $this->load();
        }
    }

    /**
     * Return cache filename.
     * 
     * @return  string
     */

    public function filename()
    {
        return $this->path . Str::hash($this->hash) . $this->ext;
    }

    /**
     * Load cache file and set collection data.
     * 
     * @return  void
     */

    private function load()
    {
        if($this->reader->exist())
        {
            $this->data()->set(json_decode($this->reader->content(), true));
        }
    }

    /**
     * Write cache file.
     * 
     * @param   string $data
     * @return  void
     */

    private function write(string $data)
    {
        if($this->reader->exist())
        {
            $this->reader->overwrite($data);
        }
        else
        {
            $this->reader->make($data);
        }
    }

    /**
     * Return cache collection.
     * 
     * @return  \Voyager\Util\Arr
     */

    private function data()
    {
        return static::$collection;
    }

    /**
     * Return true if caching is enabled.
     * 
     * @return bool
     */

    public function enabled()
    {
        return app()->cache;
    }

    /**
     * Return cache data.
     * 
     * @return  mixed
     */

    public function get()
    {
        if($this->exist())
        {
            return $this->data()->get($this->key);
        }
    }

    /**
     * Store new cache data.
     * 
     * @param   mixed $data
     * @return  bool
     */

    public function store($data)
    {
        if($this->enabled())
        {
            if($data instanceof Collection)
            {
                $data = $data->toArray();
            }
            else if($data instanceof Arr)
            {
                $data = $data->get();
            }

            $this->data()->set($this->key, $data);
            $this->write($this->data()->toJson());
        }

        return $this;
    }

    /**
     * Return true if cache exist.
     * 
     * @return bool
     */

    public function exist()
    {
        return $this->data()->hasKey($this->key);
    }

}