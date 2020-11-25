<?php

namespace Voyager\App;

use Voyager\Console\Message;
use Voyager\Facade\Str;
use Voyager\Util\Arr;

abstract class Console
{
    /**
     * Create new console instance.
     * 
     * @param   \Voyager\Util\Arr $arg
     * @param   string $method
     * @return  void
     */

    public function __construct(Arr $arg)
    {
        $msg = new Message();
        $method = null;
        $command = $arg->get(0);
        $argument = $arg->length() > 1 ? $arg->get(1) : null;
        
        if(Str::has($command, ':'))
        {
            $method = Str::break($command, ':')[1];
        }
        else
        {
            if(is_null($argument))
            {
                $method = 'main';
            }
            else
            {
                $method = $argument;
            }
        }
        
        if(is_null($method))
        {
            $msg->error('Unknown atmos command.');
        }
        else
        {
            if(method_exists($this, $method))
            {
                $this->{$method}($argument, $arg->get());
            }
            else if(method_exists($this, 'main'))
            {
                $this->{'main'}($argument, $arg->get());
            }
            else
            {
                $msg->error('Unknown atmos command.');
            }
        }
    }
}