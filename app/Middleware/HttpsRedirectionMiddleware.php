<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Str;

class HttpsRedirectionMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        if(env('APP_SECURE') && !$request->https() && !$request->localhost())
        {
            $url = new Str('https://');
            $url->append(env('APP_URL'))
                ->append($request->uri())
                ->append($request->query());

            redirect($url->get(), true);
        }
    }

}