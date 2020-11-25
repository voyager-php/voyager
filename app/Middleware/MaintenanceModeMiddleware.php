<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Facade\Str;

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
        if(Str::equal('down', [
            Str::toLower(env('APP_MODE')),
            Str::toLower($request->route('mode'))
        ]))
        {
            abort(503);
        }
    }
}