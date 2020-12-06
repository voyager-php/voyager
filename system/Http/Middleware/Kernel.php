<?php

namespace Voyager\Http\Middleware;

use Voyager\App\Request;
use Voyager\Facade\Cache;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\File\Reader;

class Kernel
{
    /**
     * Store required middlewares.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private static $middlewares;

    /**
     * Route data.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private $route;

    /**
     * Path of kernel file.
     * 
     * @var string
     */

    private $path = 'config/kernel.php';

    /**
     * Create new instance of middleware object.
     * 
     * @param   array $route
     * @param   array $type
     * @return  void
     */

    public function __construct(Collection $route)
    {
        $this->route = $route;

        if(is_null(static::$middlewares))
        {
            static::$middlewares = $this->loadMiddlewares();
        }

        $this->test(new Collection(static::$middlewares));
    }

    /**
     * Get middleware data.
     * 
     * @return  mixed
     */

    private function loadMiddlewares()
    {
        $cache = Cache::get('middleware');
        
        if(is_null($cache))
        {
            $kernel = new Reader($this->path);
            
            if($kernel->exist())
            {
                $middlewares = $kernel->require();
                Cache::store('middleware', $middlewares);

                return $middlewares;
            }
        }
        else
        {
            return $cache;
        }
    }

    /**
     * Test each middlewares.
     * 
     * @return  void
     */

    private function test(Collection $middlewares)
    {
        $list = new Arr($middlewares->bootstrap);
        $route = new Collection($middlewares->middlewares);
        $groups = new Collection($middlewares->groups);
        
        foreach($this->route->middlewares as $alias)
        {
            if($route->has($alias))
            {
                $list->push($route->{$alias});
            }
        }

        if($groups->has($this->route->middleware))
        {
            $list->merge($groups->{$this->route->middleware});
        }

        $list->unique();
        $list->set(array_values($list->get()));

        if(!$list->empty())
        {
            require path('system/Http/Middleware/helper.php');

            $index = app()->index;
            $request = new Request($this->route->toArray());
            $middleware = $list->get($index);
            $instance = new $middleware($request);
            $instance->test();

            while(app()->index < $list->length() && !app()->bypass && app()->proceed)
            {
                app()->proceed = false;
                $index = app()->index;

                $middleware = $list->get($index);
                $instance = new $middleware($request);
                $instance->test();
            }

            if(app()->index !== $list->length() && !app()->bypass)
            {
                abort(500);
            }
        }
    }
}