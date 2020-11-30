<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;
use Voyager\Util\Str;

class BlockLocationMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $location = $request->getLocation();

        foreach($request->route('location') as $country)
        {
            $test = new Str($country);
            $test->toUpper();

            if($test->equal([strtoupper($location->country_code), strtoupper($location->country)]))
            {
                abort(403);
            }
        }
    }

}