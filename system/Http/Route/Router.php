<?php

namespace Voyager\Http\Route;

use Voyager\Facade\Str;
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
     * @var array
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
        $cache = cache('routes');
        $data = [];
        
        if(is_null($cache))
        {
            $dir = new Directory('routes/');
            
            if($dir->exist())
            {
                foreach($dir->files() as $file)
                {
                    $routes = [];

                    if($file->exist() && $file->is('php'))
                    {
                        $file->require();
                        
                        if(!Route::empty())
                        {
                            foreach(Route::all() as $route)
                            {
                                $routes[] = $route->data();
                            }
                        }
                    }

                    Route::clear();

                    if(!empty($routes))
                    {
                        $data[$file->name()] = $routes;
                    }
                }
            }

            return cache('routes', $data);
        }
        else
        {
            return $cache;
        }
    }

    /**
     * Find route data.
     * 
     * @return void
     */

    public function find()
    {
        $matched = [];
        $method = strtolower(app()->request()->method());
        $uri1 = $this->uriToArray($this->uri);
        $routes = static::$cache;
        $groups = array_keys($routes);
        
        for($i = 0; $i <= (sizeof($groups) - 1); $i++)
        {
            $group = $routes[$groups[$i]];
            
            for($j = 0; $j <= (sizeof($group) - 1); $j++)
            {
                $resource = [];
                $route = $group[$j];
                $uri2 = $this->uriToArray($route['uri']);
                
                if(sizeof($uri1) === sizeof($uri2))
                {
                    $n = 0;

                    for($k = 0; $k <= (sizeof($uri2) - 1); $k++)
                    {
                        if(strtolower($uri1[$k]) === strtolower($uri2[$k]))
                        {
                            $n++;
                        }
                        else
                        {
                            $segment = $uri2[$k];
                            $name = Str::move($segment, 1, 1);
                            
                            if(Str::startWith($segment, '{') && Str::endWith($segment, '}'))
                            {
                                $resource[$name] = $uri1[$k];
                                $n++;
                            }
                        }
                    }

                    if($n === sizeof($uri2))
                    {
                        if(!empty($resource))
                        {
                            $route['resource'] = $resource;
                        }

                        $matched[] = $route;
                    }
                }
            }
        }

        if(!empty($matched))
        {
            foreach($matched as $item)
            {
                if(in_array($method, $item['verb']))
                {
                    $this->route = $item;
                    return false;
                }
            }

            if(sizeof($matched) === 1)
            {
                $this->route = $matched[0];
            }
        }
    }

    /**
     * Return route data.
     * 
     * @return  array
     */

    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Split the URI by backslash.
     * 
     * @param   string $uri
     * @return  array
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

        return explode('/', $uri);
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
        return static::$instance->getRoute();
    }

}