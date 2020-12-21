<?php

namespace Voyager\Core\Error;

use Voyager\Facade\Str;

abstract class Handler
{
    /**
     * Catch all errors and return debug view.
     * 
     * @param   int $errno
     * @param   string $errstr
     * @param   string $errfile
     * @param   string $errline
     * @return  void
     */

    public function catch($errno, $errstr, $errfile, $errline)
    {
        $codes = [];
        $start = 0;
        $line = $errline - 1;
        $end = $errline;

        if($errline > 6)
        {
            $start = $errline - 6;
        }

        if(file_exists($errfile))
        {
            $lines = $this->parseCode($errfile);

            if($lines > ($line + 6))
            {
                $end += 6;
            }

            for($i = $start; $i <= (sizeof($lines) - 1); $i++)
            {
                $codes[$i] = $lines[$i];

                if($i === $end)
                {
                    break;
                }
            }
        }

        $request = app()->request();

        $root = Str::move($_SERVER['DOCUMENT_ROOT'], 0, 6);
        $includes = [];
        $env = [];

        foreach(get_included_files() as $file)
        {
            $includes[] = Str::moveFromStart($file, $root);
        }

        foreach(app()->env as $key => $item)
        {
            if(is_string($item) || is_int($item))
            {
                $env[$key] = $item;
            }
            else if(is_bool($item))
            {
                $env[$key] = $item ? 'true' : 'false';
            }
            else if(is_null($item))
            {
                $env[$key] = 'null';
            }
        }

        $this->code = 500;
        $this->setHeaders();
        
        echo view('content.debug.index', [
            'menu'                       => [
                [
                    'key'                => 'docs',
                    'url'                => 'http://docs.voyagerframework.com',
                    'label'              => 'Documentation',
                ],
                [
                    'key'                => 'ecosystem',
                    'url'                => 'http://ecosystem.voyagerframework.com',
                    'label'              => 'Ecosystem',
                ],
                [
                    'key'                => 'github',
                    'url'                => 'https://github.com/voyager-php/voyager',
                    'label'              => 'Github',
                ],
                [
                    'key'                => 'tutorials',
                    'url'                => 'https://www.youtube.com/channel/UChPmgrewC0AcLfNHzIJCKgw',
                    'label'              => 'Youtube',
                ],
                [
                    'key'                => 'release',
                    'url'                => 'https://github.com/voyager-php/voyager/releases/tag/' . env('API_VERSION'),
                    'label'              => env('API_VERSION'),
                ],
            ],
            'type'                       => $errno,
            'message'                    => $errstr,
            'file'                       => Str::moveFromStart($errfile, $root),
            'line'                       => (int)$errline,
            'includes'                   => $includes,
            'code'                       => $codes,
            'env'                        => $env,
            'request'                    => [
                'URL'                    => $request->url(),
                'Path'                   => $request->uri(),
                'Document Root'          => $request->server('DOCUMENT_ROOT'),                 
                'Request Method'         => $request->method(),
                'User-Agent'             => $request->server('HTTP_USER_AGENT'),
                'Protocol'               => $request->protocol(),
                'Port'                   => $request->port(),
            ],
            'get'                        => $request->get()->toArray(),
            'post'                       => $request->post()->toArray(),
        ]);

        $this->terminate = true;
    }

    /**
     * Return the part of the source where error appears.
     * 
     * @param   string $file
     * @return  array
     */

    protected function parseCode(string $file)
    {
        $lines = [];

        $fn = fopen($file, 'r');

        while(!feof($fn))
        {
            $lines[] = Str::moveFromEnd(fgets($fn), PHP_EOL);
        }

        return $lines;
    }

    /**
     * Prevent PHP native error reporting and catch all errors. 
     * 
     * @return  void
     */

    protected function setup()
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);

        $that = $this;

        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($that) {
            if(env('APP_DEBUG') && !app()->request()->ajax())
            {
                $that->catch($errno, $errstr, $errfile, $errline);
            }
            else
            {
                echo' ';
            }
        });

        register_shutdown_function(function () use ($that) {
            $error = error_get_last();
            
            if(!is_null($error) && env('APP_DEBUG') && !app()->request()->ajax())
            {
                $this->shutdown = true;
                $that->catch($error['type'], $error['message'], $error['file'], $error['line']);
            }
            else
            {
                echo' ';
            }
        });
    }

}