<?php

namespace App\Console;

use Voyager\App\Console;
use Voyager\Console\Message;
use Voyager\Facade\Dir;
use Voyager\Util\File\Directory;
use Voyager\Util\File\Reader;

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
        
        foreach($dir->files() as $file)
        {
            if($file->is('php'))
            {
                $msg->success('Cache file has been cleared.');
                $file->delete();
            }
        }

        $core = new Reader('public/js/core.js');

        if($core->exist())
        {
            $msg->success('Core javascript cache file has been cleared.');
            $core->delete();
        }
    }

}