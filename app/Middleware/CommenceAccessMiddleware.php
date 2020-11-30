<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Chronos;

class CommenceAccessMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $datetime = Chronos::parse($request->route('commence'));

        if($datetime->isFuture())
        {
            abort(403);
        }
    }

}