<?php

namespace Voyager\Console;

use Voyager\Facade\Dir;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Directory;

class Command
{
    /**
     * Store .env information.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private $env;

    /**
     * Command line interface class constructor.
     * 
     * @param   array $value
     * @return  void
     */

    private function __construct(array $arg)
    {
        $this->start(new Arr($arg));
    }

    /**
     * Start command-line process.
     * 
     * @param   \Voyager\Util\Arr $arg
     * @return  void
     */

    private function start(Arr $arg)
    {
        require 'helper.php';

        $msg = new Message();
        
        if($arg->empty())
        {
            $output = [];
            exec('php atmos -h', $output);

            foreach($output as $line)
            {
                $msg->set($line);
            }
        }
        else
        {
            $break = Str::break(strtolower($arg->get(0)), ':');
            $keyword = $break[0];
            $value = $break[1] ?? null;

            if(Str::equal($keyword, ['-v', '--version']))
            {
                $msg->set('---------------------------------------------------------------')
                    ->set('VOYAGER DEVELOPMENT FRAMEWORK')
                    ->set('version 1.0.1', 'yellow')
                    ->set('---------------------------------------------------------------')
                    ->lineBreak()
                    ->set('Voyager is a web development framework written in PHP that')
                    ->set('provides you foundation to kick-start your project development.')
                    ->set('It also provides you tools and easy to use libraries to make')
                    ->set('web development fast and enjoyable.')
                    ->lineBreak()
                    ->set('voyager framework © ' . date('Y'));
            }
            else if(Str::equal($keyword, ['-h', '--help']))
            {
                $msg->lineBreak()
                    ->set('Usage:', 'yellow')
                    ->set(' php atmos [<command_name>]:[<argument_1>] [<argument_2>]')
                    ->lineBreak()
                    ->set('Options:', 'yellow')
                    ->set(' {green}-h, --help{/green}                  Display this help message.')
                    ->set(' {green}-v, --version{/green}               Display current framework version.')
                    ->set(' {green}-i, --init{/green}                  Initiate project development.')
                    ->set(' {green}-c, --clear{/green}                 Clear terminal screen.')
                    ->lineBreak()
                    ->set('Commands:', 'yellow')
                    ->set(' {green}serve{/green}                       Start PHP built-in server.')
                    ->set(' {green}up{/green}                          Set project up.')
                    ->set(' {green}down{/green}                        Set project down.')
                    ->set(' {green}env{/green}                         Return or edit .env configurations.')
                    ->set(' {green}project{/green}                     Set application deployment mode.')
                    ->set(' {green}make{/green}                        Create new resource files.')
                    ->set(' {green}cache{/green}                       Manage project caches.')
                    ->set(' {green}migrate{/green}                     Migrate database.');
            }
            else if($keyword === '--habibi')
            {
                $msg->set('Dedicated to my one an only habibi {yellow}(My Love){/yellow}, Nica Magpayo.');
            }
            else if(Str::equal($keyword, ['--init', '-i']))
            {
                $output = [];

                if(!Dir::exist('storage'))
                {
                    mkdir('storage/');
                    $msg->success('Storage directory created.');
                }

                exec('php atmos cache:clear', $output);
                exec('php atmos make:env .env', $output);
                exec('php atmos key:generate', $output);
                exec('php atmos project:debug', $output);
                exec('php atmos up', $output);

                foreach($output as $item)
                {
                    $msg->set($item);
                }
            }
            else if(Str::equal($keyword, ['--clear', '-c']))
            {
                print("\033[2J\033[;H");
            }
            else if($keyword === 'serve')
            {
                $msg->success('PHP built-in server has started.');

                if(!is_null($value))
                {
                    exec('php -S localhost:' . $value . ' -t public/');
                }
                else
                {
                    exec('php -S localhost:8080 -t public/');
                }
            }
            else if($keyword === 'project' && !is_null($value))
            {
                $value = strtolower($value);
                $output = [];
                
                if($value === 'deploy')
                {
                    exec('php atmos cache:clear', $output);
                    exec('php atmos env APP_SECURE=true', $output);
                    exec('php atmos env APP_DEBUG=false', $output);
                    exec('php atmos env APP_CACHE=true', $output);
                    exec('php atmos env APP_COMPRESS=true', $output);
                }
                else if($value === 'debug')
                {
                    exec('php atmos cache:clear', $output);
                    exec('php atmos env APP_SECURE=false', $output);
                    exec('php atmos env APP_DEBUG=true', $output);
                    exec('php atmos env APP_CACHE=false', $output);
                    exec('php atmos env APP_COMPRESS=false', $output);
                }

                foreach($output as $message)
                {
                    $msg->set($message);
                }
            }
            else if(Str::equal($keyword, ['up', 'down']))
            {
                $msg->set(exec('php atmos env APP_MODE=' . $keyword));

                if($keyword === 'up')
                {
                    $msg->success('Application is up.');
                }
                else
                {
                    $msg->error('Application is down.');
                }
            }
            else
            {
                $console = new Directory('app/Console/');

                if($console->exist())
                {
                    $files = $console->fileList();
                    $list = new Arr();

                    foreach($files as $item)
                    {
                        $list->push(strtolower(Str::break($item, '.')[0]));
                    }

                    if($list->has($keyword))
                    {
                        $index = $list->indexOf($keyword, true);
                        $file = Str::break($files[$index], '.')[0];
                        $class = 'App\Console\\' . $file;

                        new $class($arg);
                    }
                    else
                    {
                        $msg->error('Unknown atmos command.');
                    }
                }
                else
                {
                    $msg->error('Unknown atmos command.');
                }
            }
        }
    }

    /**
     * Initiate command line interface instance.
     * 
     * @param   array $argc
     * @return  $this
     */

    public static function init(array $argc)
    {
        array_shift($argc);
        return new self($argc);
    }

}