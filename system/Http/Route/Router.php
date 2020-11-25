<?php

namespace Voyager\Http\Route;

use Voyager\Facade\Cache;
use Voyager\Facade\Request;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\File\Directory;

class Router
{
    /**
     * Store router instance.
     * 
     * @var \Voyager\Http\Route\Router
     */

    private static $instance;

    /**
     * Cached routes.
     * 
     * @var mixed
     */

    private static $cache;

    /**
     * Current uri.
     * 
     * @var string
     */

    private $uri;

    /**
     * Fetched route data.
     * 
     * @var array
     */

    private $route;

    /**
     * Instantiate router class.
     * 
     * @param   string $uri
     * @return  void
     */

    private function __construct(string $uri)
    {
        $this->uri = $uri;
        
        if(is_null(static::$cache))
        {
            static::$cache = $this->loadRoutes();
        }
    
        $this->find();
    }

    /**
     * Load routes data.
     * 
     * @return  array
     */

    private function loadRoutes()
    {
        $cache = Cache::get('routes');
        $data = new Arr();
        
        if(is_null($cache))
        {
            $dir = new Directory('routes/');
            
            if($dir->exist())
            {
                foreach($dir->files() as $file)
                {
                    $routes = new Arr();

                    if($file->exist() && $file->is('php'))
                    {
                        $file->require();
                        
                        if(!Route::empty())
                        {
                            foreach(Route::all() as $route)
                            {
                                $routes->push($route->data());
                            }
                        }
                        
                        $data->set($file, $routes->get());
                    }

                    Route::clear();

                    if(!$routes->empty())
                    {
                        $data->set($file->name(), $routes->get());
                    }
                }
            }

            static::$cache = $data;
            Cache::store('routes', $data);
        }
        else
        {
            $data->set($cache);
        }

        return $data;
    }

    /**
     * Find route data.
     * 
     * @return void
     */

    public function find()
    {
        $matched = new Arr();
        $method = strtolower(Request::method());
        $uri1 = $this->uriToArray($this->uri);
        $routes = static::$cache;
        $groups = new Arr($routes->keys());
        $resource = new Arr();

        for($i = 0; $i <= ($groups->length() - 1); $i++)
        {
            $group = new Arr($routes->get($groups->get($i)));
            
            for($j = 0; $j <= ($group->length() - 1); $j++)
            {
                $route = $group->get($j);
                $uri2 = $this->uriToArray($route['uri']);
                
                if($uri1->length() === $uri2->length())
                {
                    $n = 0;

                    for($k = 0; $k <= ($uri2->length() - 1); $k++)
                    {
                        if(strtolower($uri1->get($k)) === strtolower($uri2->get($k)))
                        {
                            $n++;
                        }
                        else
                        {
                            $segment = $uri2->get($k);
                            $name = Str::move($segment, 1, 1);
                            if(Str::startWith($segment, '{') && Str::endWith($segment, '}'))
                            {
                                $resource->set($name, $uri1->get($k));
                                $n++;
                            }
                        }
                    }

                    if($n === $uri2->length())
                    {
                        if(!$resource->empty())
                        {
                            $route['resource'] = $resource->get();
                        }
                        else
                        {
                            $route['resource'] = [];
                        }

                        $matched->push($route);
                    }
                }
            }
        }

        foreach($matched->get() as $item)
        {
            if(in_array($method, $item['verb']))
            {
                $this->route = $item;
                return false;
            }
        }
    }

    /**
     * Return route data.
     * 
     * @return  \Voyager\Util\Data\Collection
     */

    public function getRoute()
    {
        return new Collection($this->route ?? []);
    }

    /**
     * Split the URI by backslash.
     * 
     * @param   string $uri
     * @return  \Voyager\Util\Arr
     */

    private function uriToArray(string $uri)
    {
        if($uri !== '/')
        {
            $uri = Str::moveFromBothEnds($uri, '/');
        }
        else {
            $uri = '';
        }

        return new Arr(explode('/', $uri));
    }

    /**
     * Instantiate router class.
     * 
     * @param   string $uri
     * @return  $this
     */

    public static function init(string $uri)
    {
        if(is_null(static::$instance))
        {
            static::$instance = new self($uri);
        }

        return static::$instance;
    }

    /**
     * Return route data.
     * 
     * @return  array
     */

    public static function data()
    {
        return static::$instance->getRoute()->toArray();
    }

}