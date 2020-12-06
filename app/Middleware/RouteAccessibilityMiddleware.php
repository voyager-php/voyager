<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Chronos;

class RouteAccessibilityMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        if(!is_null($request->route('accessibility')) && Chronos::parse($request->route('accessibility'))->isFuture())
        {
            abort(403);
        }

        if(!is_null($request->route('expiration')) && Chronos::parse($request->route('expiration'))->hasPassed())
        {
            abort(410);
        }
    }

}