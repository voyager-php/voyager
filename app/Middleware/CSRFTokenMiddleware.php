<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;

class CSRFTokenMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $token = $request->get('_token', $request->post('_token', null));

        if($request->route('csrf'))
        {
            if(!is_null($token))
            {
                if($token !== csrf_token())
                {
                    abort(403);
                }
            }
            else
            {
                abort(403);
            }
        }
    }

}