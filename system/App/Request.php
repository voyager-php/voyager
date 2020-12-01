<?php

namespace Voyager\App;

use Voyager\Http\File\DownloadManager;
use Voyager\Http\File\FileManager;
use Voyager\Http\File\UploadManager;
use Voyager\Http\Request as Data;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;

class Request
{
    /**
     * Store request data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $data;

    /**
     * Store route data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $route;

    /**
     * Store resource data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $resource;

    /**
     * Create new instance.
     * 
     * @param   array $route
     * @return  void
     */

    public function __construct(array $route)
    {
        $this->data = new Data();
        $this->route = new Arr($route);
        $this->resource = new Arr($route['resource'] ?? []);
    }

    /**
     * Return request status code.
     * 
     * @return  int
     */

    public function statusCode()
    {
        return (int)app()->code;
    }

    /**
     * Return status message.
     * 
     * @param   string $lang
     * @return  string
     */

    public function statusMessage(string $lang = 'en')
    {
        $key = 'status.code.' . app()->code . '@statuscode';
        $lang = lang($key, $lang);
    
        if($key !== $lang)
        {
            return $lang;
        }
    }

    /**
     * Return request data by dynamic method.
     * 
     * @param   string $name
     * @param   array $arguments
     * @return  mixed
     */

    public function __call(string $name, array $arguments)
    {
        if(method_exists($this->data, $name))
        {
            return $this->data->{$name}(...$arguments);
        }
    }

    /**
     * Set request header.
     * 
     * @param   string $name
     * @param   string $value
     * @return  $this
     */

    public function setHeader(string $name, string $value)
    {
        app()->header($name, $value);
        return $this;
    }

    /**
     * Return route property by key.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function route(string $key = null)
    {
        if(!is_null($key))
        {
            if($this->route->hasKey($key))
            {
                return $this->route->get($key);
            }
        }
        else
        {
            return $this->route->get();
        }
    }

    /**
     * Return resource data.
     * 
     * @param   string $name
     * @return  mixed
     */

    public function __get(string $name)
    {
        return $this->resource->get($name);
    }

    /**
     * Return default formatted response for API.
     * 
     * @param   mixed $data
     * @param   array $meta
     * @return  \Voyager\Util\Data\Collection
     */

    public function apiResponse($data = null, array $meta = [])
    {
        return new Collection([
            'code'                  => $this->statusCode(),
            'status'                => $this->statusMessage(),
            'success'               => app()->success,
            'meta'                  => $meta,
            'data'                  => $data ?? [],
        ]);
    }

    /**
     * Download specific file to browser.
     * 
     * @param   string $filename
     * @return  $this
     */

    public function download(string $filename)
    {
        $manager = new DownloadManager($filename);
        
        return $manager->exec();
    }

    /**
     * Upload file in to the server.
     * 
     * @param   string $key
     * @return  \Voyager\Http\File\UploadManager
     */

    public function upload(string $key)
    {
        return new UploadManager($key, $this);
    }

    /**
     * Show file instead of downloading it.
     * 
     * @param   string $filename
     * @return  bool
     */

    public function file(string $filename)
    {
        $manager = new FileManager($filename);

        return $manager->success();
    }

}