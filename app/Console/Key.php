<?php

namespace App\Console;

use Voyager\App\Console;
use Voyager\Console\Message;
use Voyager\Core\Env;
use Voyager\Facade\Str;
use Voyager\Util\Arr;

class Key extends Console
{
    /**
     * Return generated api key.
     * 
     * @return  string
     */

    protected function main()
    {
        $msg = new Message();
        $output = [];

        exec('php atmos env API_KEY', $output);

        foreach($output as $item)
        {
            $msg->set($item);
        }
    }

    /**
     * Generate 32 character API key.
     * 
     * @return  void
     */

    protected function generate()
    {
        $msg = new Message();
        $env = new Arr(Env::get());
        
        if(is_null($env->get('API_KEY')))
        {
            $msg->set(exec('php atmos env API_KEY=' . Str::random(32)));
            $msg->success('API key has been generated.');
        }
        else
        {
            $msg->error('API key was already generated.');
        }
    }

}
