<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;

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
        if(time() > strtotime($request->route('expiration')))
        {
            abort(410);
        }
    }

}