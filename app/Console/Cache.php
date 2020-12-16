<?php

namespace App\Console;

use Voyager\App\Console;
use Voyager\Console\Message;
use Voyager\Facade\Dir;
use Voyager\Util\File\Directory;

class Cache extends Console
{
    /**
     * Clear all cache data from cache storage.
     * 
     * @return  void
     */

    protected function clear()
    {
        $msg = new Message();
        
        if(!Dir::exist('storage/cache/'))
        {
            Dir::makeFolder('storage/', 'cache');
            $msg->success('Cache folder was successfully cleared.');
        }

        $dir = new Directory('storage/cache');
        $cleared = false;

        foreach($dir->files() as $file)
        {
            if($file->is('php'))
            {
                $cleared = true;
                $file->delete();
            }
        }

        if($cleared)
        {
            $msg->success('Cache file has been cleared.');
        }

        $html = new Directory('storage/cache/html/');

        if($html->exist())
        {
            foreach($html->files() as $file)
            {
                if($file->is('html'))
                {
                    $file->delete();
                }
            }

            $msg->success('Static html cache files have been cleared.');
        }

        $css = new Directory('public/css/static/');

        if($css->exist())
        {
            foreach($css->files() as $file)
            {
                if($file->is('css'))
                {
                    $file->delete();
                }
            }

            $msg->success('Static css cache files have been cleared.');
        }

        $js = new Directory('public/js/static/');

        if($js->exist())
        {
            foreach($js->files() as $file)
            {
                if($file->is('js'))
                {
                    $file->delete();
                }
            }

            $msg->success('Static javascript file have been deleted.');
        }
    }

}