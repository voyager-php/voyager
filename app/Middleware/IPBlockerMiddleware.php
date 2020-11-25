<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Facade\Cache;
use Voyager\Util\Arr;
use Voyager\Util\File\Reader;

class IPBlockerMiddleware extends Middleware
{
    /**
     * Location of blocked ip addresses.
     * 
     * @var string
     */

    protected $config_path = 'config/ip-blocker.php';

    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        if($this->loadIps()->has($request->client()))
        {
            abort(403);
        }
    }

    /**
     * Load and return blocked ip addresses.
     * 
     * @return  \Voyager\Util\Arr
     */

    protected function loadIps()
    {
        $cache = Cache::get('ip-address');
        $list = new Arr();

        if(is_null($cache))
        {
            $file = new Reader($this->config_path);

            if($file->exist())
            {
                $list->set($file->require());
                Cache::store('ip-address', $list->get());
            }
        }
        else
        {
            $list->set($cache);
        }

        return $list;
    }

}