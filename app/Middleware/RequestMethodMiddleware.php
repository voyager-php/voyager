<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;

class RequestMethodMiddleware extends Middleware
{
    /**
     * Restful allowed methods.
     * 
     * @var array
     */

    private static $ALLOWED_METHODS = ['get', 'post', 'put', 'patch', 'delete', 'options'];

    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $method = strtolower($request->method());
        $_verb = $request->post('_verb', null);

        // In case put, patch and delete verbs are not supported,
        // spoof the application by faking the verb from POST
        // parameter.
        
        if($method === 'post' && !is_null($_verb))
        {
            $_verb = strtolower($_verb);
            
            if(in_array($_verb, static::$ALLOWED_METHODS))
            {
                $method = $_verb;
            }
        }

        // Check if request method is supported by the application.
        
        if(!in_array($method, static::$ALLOWED_METHODS))
        {
            abort(501);
        }

        // If wrong method was used in request.

        if(!in_array($method, $request->route('verb')))
        {
            abort(405);
        }
    }

}