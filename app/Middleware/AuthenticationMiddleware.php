<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Http\Security\Authentication;

class AuthenticationMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */
    
    protected function handle(Request $request)
    {
        if($request->route('authentication'))
        {
            $auth = new Authentication();
            
            if(!$auth->authenticated())
            {
                if($request->ajax() || $request->route('ajax'))
                {
                    abort(403);
                }
                else
                {
                    redirect($auth->config()->redirection);
                }
            }
        }
    }
}