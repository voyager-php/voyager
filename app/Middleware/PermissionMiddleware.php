<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Facade\Auth;

class PermissionMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $permission = $request->route('permission');

        if(!empty($permission) && !in_array(Auth::type(), $permission))
        {
            abort(403);
        }
    }
}