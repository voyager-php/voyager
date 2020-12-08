<?php

namespace Voyager\Http;

use Voyager\Database\DBResult;
use Voyager\Util\Arr;
use Voyager\Util\Data\Bundle;
use Voyager\Util\Data\Collection;

class Response
{
    /**
     * Default response content type.
     * 
     * @var string
     */

    private $contentType = 'text/html';

    /**
     * Cache the response here.
     * 
     * @var mixed
     */

    private $response;

    /**
     * Response output string.
     * 
     * @var string
     */

    private $output;

    /**
     * Create new instance of respone object.
     * 
     * @param   mixed $response
     * @return  void
     */

    public function __construct($response = null)
    {
        $this->response = $response;
        $this->evaluate();
    }

    /**
     * Analyze the response data.
     * 
     * @return  void
     */

    private function evaluate()
    {
        $response = $this->response;

        if(is_array($response))
        {
            if(empty($response))
            {
                $this->abort();
            }

            $this->setJsonOutput(json_encode($response));
        }
        else if($response instanceof Arr || $response instanceof Collection || $response instanceof Bundle)
        {
            if($response->empty())
            {
                $this->abort();
            }

            $this->setJsonOutput($response->toJson());
        }
        else if($response instanceof DBResult)
        {
            $response = $response->fetch();

            if($response->empty())
            {
                $this->abort();
            }

            $this->setJsonOutput($response->toJson());
        }
        else if(is_string($response))
        {
            if(empty($response))
            {
                $this->abort();
            }

            $this->output = $response;
        }
        else if(is_numeric($response) || is_int($response))
        {
            $this->output = (string)$response;
        }
    }

    /**
     * Abort request.
     * 
     * @return  void
     */

    private function abort(int $code = 204)
    {
        abort($code);
    }

    /**
     * Set json response.
     * 
     * @param   string $output
     * @return  void
     */

    private function setJsonOutput(string $output)
    {
        json_decode($output);

        if(json_last_error() === JSON_ERROR_NONE)
        {
            $this->contentType = 'application/json';
        }
        
        $this->output = $output;
    }

    /**
     * Return the output string.
     * 
     * @return  string
     */

    public function result()
    {
        if(env('APP_COMPRESS'))
        {
            return preg_replace('!\s+!', ' ', str_replace(['\r', '\n'], ' ', trim($this->output)));
        }
        else
        {
            return $this->output;
        }
    }

    /**
     * Return response content type.
     * 
     * @return  string
     */

    public function contentType()
    {
        return $this->contentType;
    }

}