<?php

namespace Voyager\Http\File;

use Voyager\App\Request;
use Voyager\Facade\Str;

class DownloadManager
{
    /**
     * Filename of the file to download.
     * 
     * @var string
     */

    private $filename;

    /**
     * Request object instance.
     * 
     * @var \Voyager\App\Request
     */

    private $request;

    /**
     * Create new instance of download manager.
     * 
     * @param   string $filename
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    public function __construct(string $filename, Request $request)
    {
        $this->filename = Str::moveFromStart($filename, '/');
        $this->request = $request;
    }

    /**
     * Execute file download.
     * 
     * @return  bool
     */

    public function exec()
    {
        $file = path($this->filename);

        if(file_exists($file))
        {
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($file));
            readfile($file);

            return true;
        }

        return false;
    }

}