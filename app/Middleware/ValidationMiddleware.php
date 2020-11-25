<?php

namespace App\Middleware;

use Voyager\App\Middleware;
use Voyager\App\Request;

class ValidationMiddleware extends Middleware
{
    /**
     * Handle request logic.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    protected function handle(Request $request)
    {
        $validation = $request->route('validation');

        if(!is_null($validation))
        {
            $validate = new $validation($request);
            
            if(!$validate->success())
            {
                $errors = null;

                if($request->route('ajax') || $request->ajax())
                {
                    $errors = [
                        'errors' => $validate->getErrors()
                    ];
                }

                abort(400, $errors);
            }
        }
    }
}