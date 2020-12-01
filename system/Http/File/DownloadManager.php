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
     * Create new instance of download manager.
     * 
     * @param   string $filename
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    public function __construct(string $filename)
    {
        $this->filename = Str::moveFromStart($filename, '/');
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
            app()->terminate = true;
        }

        return false;
    }

}