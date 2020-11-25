<?php

namespace Voyager\Util\Http;

class Curl
{
    private $param = [];
    private $method;
    private $url;
    private $output;
    private $duration;

    private function __construct(string $method, string $url)
    {
        $this->method = $method;
        $this->url = $url;
    }

    /**
     * EXECUTE 
     * ----------------------------------
     * Build CURL operation.
     */

    public function param(string $key, $value)
    {
        $this->param[$key] = urldecode($value);
        return $this;
    }

    /**
     * EXECUTE 
     * ----------------------------------
     * Build CURL operation.
     */

    public function exec()
    {
        $start = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set request method.

        if($this->method === 'post')
        {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        else if($this->method !== 'get')
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->method));
        }

        // Execute curl request.

        $this->output = curl_exec($ch);

        // close curl resource.

        curl_close($ch);
        $end = microtime(true);

        // Compute execution duration.

        $this->duration = number_format($end - $start, 2);
    }

    /**
     * BENCHMARK
     * ----------------------------------
     * Return computed execution duration
     * of CURL request.
     */

    public function duration()
    {
        return $this->duration;
    }

    /**
     * OUTPUT
     * ----------------------------------
     * Return CURL output in string.
     */

    public function output()
    {
        return $this->output;
    }

    /**
     * GET CURL
     * ----------------------------------
     * Create get curl instance.
     */

    public static function get(string $url)
    {
        return new self('get', $url);
    }

    /**
     * POST CURL
     * ----------------------------------
     * Create post curl instance.
     */

    public static function post(string $url)
    {
        return new self('post', $url);
    }

    /**
     * PUT CURL
     * ----------------------------------
     * Create put curl instance.
     */

    public static function put(string $url)
    {
        return new self('put', $url);
    }

    /**
     * DELETE CURL
     * ----------------------------------
     * Create delete curl instance.
     */

    public static function delete(string $url)
    {
        return new self('delete', $url);
    }

}