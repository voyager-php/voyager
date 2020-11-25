<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Arr;
use Voyager\Util\Http\Session;

class RateLimiterMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $uri = $request->uri();
        $session = new Session('limiter');
        $ago = round(time() - 60);
        $arr = new Arr();

        if(!$session->has($uri))
        {
            $session->set($uri, []);
        }

        foreach($session->get($uri) as $timestamp)
        {
            $arr->push($timestamp);
        }

        $session->set($uri, $arr->get());

        $arr->truncate();

        foreach($session->get($uri) as $timestamp)
        {
            if($timestamp >= $ago)
            {
                $arr->push($timestamp);
            }
        }

        $session->set($uri, $arr->get());

        if(env('APP_REQUEST_LIMIT') <= $arr->length())
        {
            abort(429);
        }
    }

}