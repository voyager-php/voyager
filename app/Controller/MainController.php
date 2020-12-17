<?php

namespace App\Controller;

use Voyager\App\Controller;
use Voyager\App\Request;

class MainController extends Controller
{
    /**
     * Return index method resource.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function index(Request $request)
    {
        return view('content.landing');
    }

}