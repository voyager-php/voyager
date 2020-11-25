<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Facade\Cache;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Reader;

class CrossOriginMiddleware extends Middleware
{
    /**
     * Cors config file path.
     * 
     * @var string
     */

    protected $config_path = 'config/cors.php';

    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $origins = $this->loadRequestOrigins();
        $list = new Arr();

        foreach($origins as $origin)
        {
            $list->push(Str::remove($origin, 'https://'));
        }

        if($list->has($request->origin()))
        {
            abort(401);
        }
    }

    /**
     * Return all origins that can have resource
     * sharing with your application. 
     * 
     * @return  \Voyager\Util\Arr
     */

    protected function loadRequestOrigins()
    {
        $cache = Cache::get('cross-origin');
        $list = new Arr();

        if(is_null($cache))
        {
            $file = new Reader($this->config_path);

            if($file->exist())
            {
                $list->set($file->require());
                Cache::store('cross-origin', $list->get());
            }
        }
        else
        {
            $list->set($cache->toArray());
        }

        return $list;
    }

}