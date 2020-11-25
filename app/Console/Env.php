<?php

namespace App\Console;

use Voyager\App\Console;
use Voyager\Console\Message;
use Voyager\Core\Env as DotEnv;
use Voyager\Facade\File;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Reader;

class Env extends Console
{
    /**
     * Return or update .env configurations.
     * 
     * @param   string $key
     * @return  void 
     */

    protected function main(string $key = null)
    {
        $msg = new Message();

        if(!File::exist('.env'))
        {
            $msg->set(exec('php atmos make:env .env'));
        }

        $env = new Arr(DotEnv::get());

        if(!is_null($key))
        {
            if(Str::has($key, '='))
            {
                $break = Str::break($key, '=');
                $name = strtoupper($break[0]);
                $value = $break[1];
                $file = new Reader('.env');
                $data = new Arr();

                foreach($file->lines(false) as $line)
                {
                    if(Str::startWith($line, $name . '='))
                    {
                        $data->push($key);
                    }
                    else
                    {
                        $data->push($line);
                    }
                }

                $file->overwrite($data->implode(PHP_EOL));
                $msg->set('{yellow}' . $name . '{/yellow} {green}was successfully updated.{/green}')
                    ->lineBreak();
            }
            else
            {
                if($env->hasKey($key))
                {
                    $val = $env->get($key);

                    if(is_bool($val))
                    {
                        if($val === true)
                        {
                            $val = 'true';
                        }
                        else
                        {
                            $val = 'false';
                        }
                    }
                    else if(is_null($val))
                    {
                        $val = 'null';
                    }

                    $msg->set($val);
                }
            }
        }
        else
        {
            foreach($env->get() as $key => $value)
            {
                $val = $value;

                if(is_bool($val))
                {
                    if($val === true)
                    {
                        $val = 'true';
                    }
                    else
                    {
                        $val = 'false';
                    }
                }
                else if(is_null($val))
                {
                    $val = 'null';
                }

                $msg->set('{yellow}' . $key . ':{/yellow} ' . $val);
            }
        }
    }

}