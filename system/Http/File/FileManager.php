<?php

namespace Voyager\Http\File;

use Voyager\Util\File\Reader;

class FileManager
{
    /**
     * Name of the file to read.
     * 
     * @var string
     */

    private $filename;

    /**
     * Determine if displaying file succeed.
     * 
     * @var bool
     */

    private $success = true;

    /**
     * Create new instance of file manager.
     * 
     * @param   string $filename
     * @return  void
     */

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->openFile();
    }

    /**
     * Test and read file to open.
     * 
     * @return  void
     */

    private function openFile()
    {
        $file = new Reader($this->filename);

        if($file->exist())
        {
            $contentType = $this->getContentType(strtolower($file->extension()));
            
            if(!is_null($contentType))
            {
                app()->contentType = $contentType;
                app()->header('Content-Length', $file->size());
                
                readfile(path($this->filename));
                app()->terminate = true;
            }
        }

        $this->success = false;
    }

    /**
     * Return the content type of the file.
     * 
     * @param   string $extension
     * @return  string
     */

    private function getContentType(string $extension)
    {
        $accepted = [
            'png'               => 'image/png',
            'css'               => 'text/css',
            'js'                => 'text/javascript',
            'jpg'               => 'image/jpeg',
            'json'              => 'application/json',
            'pdf'               => 'application/pdf',
            'txt'               => 'text/plain',
            'xml'               => 'text/xml',
        ];

        if(array_key_exists($extension, $accepted))
        {
            return $accepted[$extension];
        }
    }

    /**
     * Return true if a success.
     * 
     * @return  bool
     */

    public function success()
    {
        return $this->success;
    }

}