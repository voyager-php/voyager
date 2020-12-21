<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Facade\Str;
use Voyager\Util\File\Reader;

class CrossOriginMiddleware extends Middleware
{
    /**
     * Cors config file path.
     * 
     * @var string
     */

    private $config_path = 'config/cors.php';

    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $origins = $this->loadRequestOrigins();
        $list = [];

        foreach($origins as $origin)
        {
            $list[] = Str::remove($origin, 'https://');
        }

        if(in_array($request->origin(), $list))
        {
            abort(401);
        }
    }

    /**
     * Return all origins that can have resource
     * sharing with your application. 
     * 
     * @return  array
     */

    private function loadRequestOrigins()
    {
        $cache = cache('cross-origin');
        
        if(is_null($cache))
        {
            $file = new Reader($this->config_path);
            $list = [];

            if($file->exist())
            {
                $list = cache('cross-origin', $file->require());
            }

            return $list;
        }
        else
        {
            return $cache;
        }
    }

}