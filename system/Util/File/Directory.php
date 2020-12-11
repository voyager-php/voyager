<?php

namespace Voyager\Util\File;

use Voyager\Facade\File;
use Voyager\Facade\Str;
use Voyager\Util\Arr;

class Directory
{
    /**
     * Store files.
     * 
     * @var \Voyager\Util\Arr
     */

    private $files;

    /**
     * Store reader collections.
     * 
     * @var \Voyager\Util\Arr
     */

    private $collection;

    /**
     * Store directories.
     * 
     * @var \Voyager\Util\Arr
     */

    private $directories;

    /**
     * Directory path.
     * 
     * @var string
     */

    private $path;

    /**
     * Directory location.
     * 
     * @var string
     */

    private $location;

    /**
     * Instantiate new directory instance.
     * 
     * @param   string $path
     * @return  void
     */

    public function __construct(string $path)
    {
        $this->path = Str::moveFromBothEnds($path, '/') . '/';
        $this->location = path($this->path);
        $this->files = new Arr();
        $this->collection = new Arr();
        $this->directories = new Arr();
    }

    /**
     * Return directory path.
     * 
     * @return  string
     */

    public function path()
    {
        return $this->path;
    }

    /**
     * Return true if directory exist.
     * 
     * @return  bool
     */

    public function exist()
    {
        return is_dir($this->location) && file_exists($this->location);
    }

    /**
     * Return array of directory files.
     * 
     * @return array
     */

    public function files()
    {
        if($this->collection->empty())
        {
            foreach($this->fileList() as $file)
            {
                if(!is_dir($file))
                {
                    $this->files->push($file);
                    $this->collection->push(new Reader($this->path . $file));
                }
                else
                {
                    $this->directories->push($file);
                }
            }
        }

        return $this->collection->get();
    }

    /**
     * Return list of filenames.
     * 
     * @return  array
     */

    public function fileList()
    {
        return array_values(array_diff(scandir($this->location), ['.', '..']));
    }

    /**
     * Return array of directories.
     * 
     * @return array
     */

    public function subDirectories()
    {
        $this->files();

        return $this->directories->get();
    }

    /**
     * Return subdirectory inside directory.
     * 
     * @param   string $path
     * @return  \Voyager\Util\File\Directory
     */

    public function directory(string $path)
    {
        return new self($this->path . Str::moveFromBothEnds($path, '/') . '/');
    }

    /**
     * Return file.
     * 
     * @param   string $filename
     * @return  \Voyager\Util\File\Reader
     */

    public function file(string $filename)
    {
        if($this->has($filename))
        {
            return new Reader($this->path . $filename);
        }
    }

    /**
     * Check if file exist from directory.
     * 
     * @param   string $filename
     * @return  bool
     */

    public function has(string $filename)
    {
        $this->files();
        return $this->files->has($filename);
    }

    /**
     * Create folder inside directory.
     * 
     * @param   string $name
     * @return  \Voyager\Util\File\Directory
     */

    public function makeFolder(string $name)
    {
        $dir = new self($this->path . $name . '/');
        
        if(!$dir->exist())
        {
            mkdir(path($this->path . $name));
        }

        return $dir;
    }

    /**
     * Create file inside the directory.
     * 
     * @param   string $filename
     * @param   string $data
     * @return  \Voyager\Util\File\Reader
     */

    public function make(string $filename, string $data)
    {
        $reader = new Reader($this->path . $filename, $data);

        if(!$reader->exist())
        {
            $reader->make($data);
        }

        return $reader;
    }

}