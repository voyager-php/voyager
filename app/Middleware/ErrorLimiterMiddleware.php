<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Arr;
use Voyager\Util\Http\Session;

class ErrorLimiterMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $hit = 0;
        $uri = $request->uri();
        $session = new Session('errors');
        $ago = round(time() - 60);
        $arr = new Arr();
        
        if(!$session->has($uri))
        {
            $session->set($uri, []);
        }

        foreach($session->get($uri) as $timestamp)
        {
            if($timestamp >= $ago)
            {
                $arr->push($timestamp);
                $hit++;
            }
        }

        $session->set($uri, $arr->get());

        if(env('APP_ERROR_LIMIT') <= $hit)
        {
            abort(429);
        }
    }

}