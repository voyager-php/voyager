<?php

namespace Voyager\Http\File;

use Voyager\App\Request;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\Chronos;
use Voyager\Util\Data\Collection;
use Voyager\Util\File\Directory;

class UploadManager
{
    /**
     * Maximum size of file to upload.
     * 
     * @var int
     */

    private $max;

    /**
     * Directory where uploaded file will be saved.
     * 
     * @var \Voyager\Util\File\Directory
     */

    private $directory;

    /**
     * Store accepted file extensions.
     * 
     * @var \Voyager\Util\Arr
     */

    private $accept;

    /**
     * Store request file data.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private $data;

    /**
     * If file upload is required from the validation.
     * 
     * @var bool
     */

    private $required = true;

    /**
     * Create new upload manager instance.
     * 
     * @param   string $key
     * @return  void
     */

    public function __construct(string $key, Request $request)
    {
        $this->key = $key;
        $this->request = $request;
        $this->accept = new Arr();
        $this->data = $request->file($key);

        if(!Str::equal($request->method(), ['POST', 'PUT', 'PATCH']))
        {
            abort(405);
        }
    }

    /**
     * Set the directory where to save the uploaded file.
     * 
     * @param   string $directory
     * @return  $this
     */

    public function to(string $directory)
    {
        $this->directory = new Directory($directory);

        return $this;
    }

    /**
     * Set maximum file size to upload.
     * 
     * @param   int $kilobyte
     * @return  $this
     */

    public function max(int $kilobyte)
    {
        $this->max = (1000 * $kilobyte);

        return $this;
    }

    /**
     * Set file upload as required or optional.
     * 
     * @return  $this
     */

    public function optional()
    {
        $this->required = true;

        return $this;
    }

    /**
     * Set file extension to accept.
     * 
     * @param   mixed $extension
     * @return  $this
     */

    public function accept($extension)
    {
        if(is_string($extension))
        {
            $this->accept->push($extension);
        }
        else if(is_array($extension))
        {
            $this->accept->set($extension);
        }

        return $this;
    }

    /**
     * Move uplaoded file in to the destination directory.
     * 
     * @return  mixed
     */

    public function move(string $rename = null)
    {
        $now = Chronos::now()->format('Y-m-d');

        if(is_null($rename))
        {
            $hash = Str::hash($now . '-' . Str::random(10));
        }
        else
        {
            $hash = Str::hash($now . '-' . $rename);
        }

        if(!is_null($this->data) && $this->data->error === UPLOAD_ERR_OK && $this->data->size <= $this->max)
        {
            $ext = new Arr(explode('.', strtolower($this->data->name)));
            $ext = $ext->last();

            if($this->accept->has($ext) || $this->accept->empty())
            {
                if($this->directory->exist())
                {
                    if(!move_uploaded_file($this->data->tmp_name, path($this->directory->path()) . $hash . '.' . $ext))
                    {
                        abort(400);
                    }

                    return new Collection([
                        'hash'                  => $hash,
                        'extension'             => $ext,
                    ]);
                }
            }
        }

        if($this->required)
        {
            abort(400);
        }

        return false;
    }

}