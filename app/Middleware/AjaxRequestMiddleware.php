<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;

class AjaxRequestMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        if(!$request->ajax() && $request->route('ajax'))
        {
            abort(400);
        }
    }

}