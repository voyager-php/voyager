<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Chronos;

class RouteExpirationMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */
    
    protected function handle(Request $request)
    {
        if(Chronos::parse($request->route('expiration'))->hasPassed())
        {
            abort(410);
        }
    }

}