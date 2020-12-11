<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
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
        if(in_array($request->client(), $this->loadIps()))
        {
            abort(403);
        }
    }

    /**
     * Load and return blocked ip addresses.
     * 
     * @return  array
     */

    protected function loadIps()
    {
        $cache = cache('ip-address');
        
        if(is_null($cache))
        {
            $file = new Reader($this->config_path);
            $data = [];
            
            if($file->exist())
            {
                $data = cache('ip-address', $file->require());
            }

            return $data;
        }
        else
        {
            return $cache;
        }
    }

}