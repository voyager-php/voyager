<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;

class MaintenanceModeMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        if(strtolower(env('APP_MODE')) === 'down' || strtolower($request->route('mode')) === 'down')
        {
            abort(503);
        }
    }
}