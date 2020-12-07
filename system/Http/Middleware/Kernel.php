<?php

namespace Voyager\Http\Middleware;

use Voyager\App\Request;
use Voyager\Facade\Cache;
use Voyager\Facade\Str;
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
            else if(Str::startWith($alias, 'App\Middleware'))
            {
                $list->push($alias);
            }
        }

        if($groups->has($this->route->middleware))
        {
            $list->merge($groups->{$this->route->middleware});
        }
        else
        {
            if($route->has($this->route->middleware))
            {
                $list->push($route->{$this->route->middleware});
            }
        }

        $list->unique();
        $list->set($list->values());

        if(!$list->empty())
        {
            $middlewares = static::$middlewares['middlewares'];
            $queue = [];

            foreach($list->get() as $value)
            {
                if(!Str::startWith($value, 'App\Middleware'))
                {
                    $queue[] = $middlewares[$value];
                }
                else
                {
                    $queue[] = $value;
                }
            }

            require path('system/Http/Middleware/helper.php');

            $index = app()->index;
            $request = new Request($this->route->toArray());
            $middleware = $queue[$index];
            $instance = new $middleware($request);
            $instance->test();

            while(app()->index < sizeof($queue) && !app()->bypass && app()->proceed)
            {
                app()->proceed = false;
                $index = app()->index;

                $middleware = $queue[$index];
                $instance = new $middleware($request);
                $instance->test();
            }

            if(app()->index !== sizeof($queue) && !app()->bypass)
            {
                abort(500);
            }
        }
    }
}