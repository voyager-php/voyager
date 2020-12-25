<?php

namespace App\Controller\Internal;

use Voyager\App\Controller;
use Voyager\App\Request;
use Voyager\Facade\File;

class ErrorStatusController extends Controller
{
    /**
     * Return http status response in each request.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function index(Request $request)
    {
        $code = $request->statusCode();

        if($request->route('ajax') || $request->ajax())
        {
            return $request->apiResponse();
        }
        else
        {
            $message = $request->statusMessage();

            if(File::exist('resource/view/content/errors/' . $code . '.html'))
            {
                $errordoc = 'content.errors.' . $code;
            }
            else
            {
                $errordoc = 'content.errors.default';
            }

            return view($errordoc, [
                'title'     => $code . ' - ' . $message,
                'code'      => $code,
                'message'   => $message,
            ]);
        }
    }

}