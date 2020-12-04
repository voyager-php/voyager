<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
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
        $session = new Session('errors');
        $ago = round(time() - 60);
        $arr = [];
        
        if(!$session->has('logs'))
        {
            $session->set('logs', []);
        }

        foreach($session->get('logs') as $timestamp)
        {
            if($timestamp >= $ago)
            {
                $arr[] = $timestamp;
                $hit++;
            }
        }

        $session->set('logs', $arr);

        if(env('APP_ERROR_LIMIT') <= $hit)
        {
            abort(429);
        }
    }

}