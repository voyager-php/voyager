<?php

namespace Voyager\Util\File;

use Voyager\Facade\Str;
use Voyager\Util\Arr;

class Reader
{
    /**
     * Store file path.
     * 
     * @var string
     */

    private $file;

    /**
     * Store reader path.
     * 
     * @var string
     */

    private $path;

    /**
     * Store lines from the file.
     * 
     * @var \Voyager\Util\Arr
     */

    private $lines;

    /**
     * Create new file reader instance.
     * 
     * @param   string $path
     * @return  void
     */

    public function __construct(string $path)
    {
        $this->file = Str::moveFromBothEnds($path, '/');
        $this->path = path($this->file);
        $this->lines = new Arr();
    }

    /**
     * Return file reader path.
     * 
     * @return  string
     */

    public function path()
    {
        return $this->file;
    }

    /**
     * Return file extension.
     * 
     * @return  string
     */

    public function extension()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Return true if file extension matched.
     * 
     * @param   string $ext
     * @return  bool
     */

    public function is(string $ext)
    {
        return $this->extension() === $ext;
    }

    /**
     * Return filename.
     * 
     * @return  string
     */

    public function name()
    {
        return basename($this->path, '.' . $this->extension());
    }

    /**
     * Return file size.
     * 
     * @return  int
     */

    public function size()
    {
        return (int)filesize($this->path);
    }

    /**
     * Return true if file is empty.
     * 
     * @return  bool
     */

    public function empty()
    {
        return $this->size() === 0;
    }

    /**
     * Return last modified timestamp.
     * 
     * @return  string
     */

    public function lastModified()
    {
        return date('Y-m-d H:i:s', filemtime($this->path));
    }

    /**
     * Return mime content type.
     * 
     * @return  string
     */

    public function contentType()
    {
        return mime_content_type($this->path);
    }

    /**
     * Return true if file exist.
     * 
     * @return  bool
     */

    public function exist()
    {
        return file_exists($this->path);
    }

    /**
     * Return true if file is readable.
     * 
     * @return bool
     */

    public function readable()
    {
        return is_readable($this->path);
    }

    /**
     * Return true if file is writable.
     * 
     * @return  bool
     */

    public function writable()
    {
        return is_writable($this->path);
    }

    /**
     * Require PHP file.
     * 
     * @return  mixed
     */

    public function require()
    {
        if($this->is('php') && $this->exist())
        {
            return require_once $this->path;
        }

        return $this;
    }

    /**
     * Delete file.
     * 
     * @return void
     */

    public function delete()
    {
        if($this->exist() && $this->readable())
        {
            unlink($this->path);
        }
    }

    /**
     * Return file content as string.
     * 
     * @return mixed
     */

    public function content()
    {
        if($this->exist() && $this->readable())
        {
            return file_get_contents($this->path);
        }
    }

    /**
     * Replace the content of the file.
     * 
     * @param   mixed $data
     * @return  void
     */

    public function overwrite($data)
    {
        if($this->exist())
        {
            if(is_array($data))
            {
                $data = json_encode($data);
            }

            file_put_contents($this->path, $data);
        }
    }

    /**
     * If file don't exist, create file.
     * 
     * @param   string $data
     * @return  void
     */

    public function make(string $data)
    {
        if(!$this->exist())
        {
            $file = fopen($this->path, 'a+');
            fwrite($file, $data);
            fclose($file);
        }
    }

    /**
     * Return array of lines from the file.
     * 
     * @param   bool $diff
     * @return  array
     */

    public function lines(bool $diff = true)
    {
        if($this->exist() && $this->readable() && $this->lines->empty())
        {
            $fn = fopen($this->path, 'r');

            while(!feof($fn))
            {
                $this->lines->push(Str::moveFromEnd(trim(fgets($fn)), PHP_EOL));
            }
        }

        if($diff)
        {
            return array_diff($this->lines->get(), ["\n", "\r", '']);
        }
        else
        {
            return $this->lines->get();
        }
    }

    /**
     * Return line using index number.
     * 
     * @param   int $index
     * @return  mixed
     */

    public function line(int $index)
    {
        return $this->lines->get($index);
    }

    /**
     * Append new line in file contents.
     * 
     * @param   string $text
     * @return  void
     */

    public function newLine(string $text)
    {
        $this->lines->push($text);
        $this->overwrite($this->lines->implode(PHP_EOL));
    }

}