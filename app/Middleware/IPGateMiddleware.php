<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\File\Reader;

class IPGateMiddleware extends Middleware
{
    /**
     * Location of blocked ip addresses.
     * 
     * @var string
     */

    private $config_path = 'config/ip.php';

    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $config = $this->config();

        if(in_array($request->client(), $config['list']))
        {
            if($config['block'])
            {
                abort(403);
            }
        }
        else
        {
            if(!$config['block'])
            {
                abort(403);
            }
        }
    }

    /**
     * Load and return blocked ip addresses.
     * 
     * @return  array
     */

    private function config()
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