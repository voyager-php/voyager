<?php

namespace Voyager\Http;

use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;

class Request
{
    /**
     * Store server data.
     * 
     * @return  \Voyager\Util\Arr
     */

    private $server;

    /**
     * Create new request class instance.
     */

    public function __construct()
    {
        $this->server = new Arr($_SERVER);
    }

    /**
     * Return server data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function server(string $key)
    {
        if($this->server->hasKey($key))
        {
            return $this->server->get($key);
        }
    }

    /**
     * Return the request query string.
     * 
     * @return  string
     */

    public function query()
    {
        return $this->server('QUERY_STRING');
    }

    /**
     * Return the whole requested URL.
     * 
     * @return  string
     */

    public function url()
    {
        $query = $this->query();
        return url($this->uri()) . (!is_null($query) ? '?' : '') . $query;
    }

    /**
     * Return the base url.
     * 
     * @return  string
     */

    public function baseURL()
    {
        $url = ($this->https() ? 'https' : 'http') . '://' . parse_url($this->url(), PHP_URL_HOST);

        if((int)$this->port() !== 80)
        {
            $url .= ':' . $this->port();
        }

        return $url . '/';
    }

    /**
     * Return the URI from the request.
     * 
     * @return  string
     */

    public function uri()
    {
        $uri = Str::break($this->server('REQUEST_URI'), '?')[0];
        
        if(Str::endWith($uri, '/') && $uri !== '/')
        {
            $uri = Str::move($uri, 0, 1);
        }

        return $uri;
    }

    /**
     * Return request method.
     * 
     * @return  string
     */

    public function method()
    {
        $method = strtoupper($this->server('REQUEST_METHOD'));
    
        if($method !== 'GET')
        {
            $spoof = $this->get('_verb', $this->post('_post', null));
        
            if(!is_null($spoof))
            {
                $method = $spoof;
            }
        }

        return $method;
    }

    /**
     * Return true if request is https.
     * 
     * @return  bool
     */

    public function https()
    {
        return $this->server->hasKey('HTTPS') && !empty($this->server('HTTPS')) && $this->server('HTTPS') !== 'off';
    }

    /**
     * Return get parameter value.
     * 
     * @param   string $key
     * @param   mixed $default
     * @return  mixed
     */

    public function get(string $key = null, $default = null)
    {
        $param = new Arr();
            
        foreach($_GET as $keyword => $value)
        {
            $param->set($keyword, urldecode($value));
        }

        if(is_null($key))
        {   
            return $param->toCollection();
        }
        else
        {
            return $param->hasKey($key) ? $param->get($key) : $default;
        }
    }

    /**
     * Return post parameter value.
     * 
     * @param   string $key
     * @param   string $default
     * @return  mixed
     */

    public function post(string $key = null, $default = null)
    {
        $param = new Arr();

        foreach($_POST as $keyword => $value)
        {
            $param->set($keyword, urldecode($value));
        }

        if(is_null($key))
        {
            return $param->toCollection();
        }
        else
        {
            return $param->hasKey($key) ? $param->get($key) : $default;
        }
    }

    /**
     * Return server file data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function file(string $key)
    {
        $files = new Arr($_FILES);

        if($files->hasKey($key))
        {
            $file = new Arr($files->get($key));

            return $file->toCollection();
        }
    }

    /**
     * Return server protocol.
     * 
     * @return  string
     */

    public function protocol()
    {
        return $this->server('SERVER_PROTOCOL');
    }

    /**
     * Return server port.
     * 
     * @return  int
     */

    public function port()
    {
        return $this->server('SERVER_PORT');
    }

    /**
     * Return if request is localhost.
     * 
     * @return  bool
     */

    public function localhost()
    {
        return in_array($this->server('REMOTE_ADDR'), ['127.0.0.1', '::1']);
    }

    /**
     * Return true if request is ajax.
     * 
     * @return bool
     */

    public function ajax()
    {
        return $this->server->hasKey('HTTP_X_REQUESTED_WITH') && $this->server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Return client ip address.
     * 
     * @return  string
     */

    public function client()
    {
        if($this->server->hasKey('HTTP_CLIENT_IP'))
        {
            return $this->server('HTTP_CLIENT_IP');
        }
        else if($this->server->hasKey('HTTP_X_FORWARDED_FOR'))
        {
            return $this->server('HTTP_X_FORWARDED_FOR');
        }
        else
        {
            return $this->server('REMOTE_ADDR');
        }
    }

    /**
     * Return domain of origin of request.
     * 
     * @return  string
     */

    public function origin()
    {
        if($this->server->hasKey('HTTP_ORIGIN') && $this->validateIP('HTTP_ORIGIN'))
        {
            return $this->server('HTTP_ORIGIN');
        }
        else if($this->server->hasKey('HTTP_REFERER') && $this->validateIP('HTTP_REFERER'))
        {
            return $this->server('HTTP_REFERER');
        }
        else
        {
            if($this->validateIP('REMOTE_ADDR'))
            {
                return $this->server('REMOTE_ADDR');
            }
        }
    }

    /**
     * Validate IP address from string.
     * 
     * @param   string $key
     * @return  bool
     */

    private function validateIP(string $key)
    {
        return filter_var($this->server($key), FILTER_VALIDATE_IP);
    }

    /**
     * Return request started.
     * 
     * @return  float 
     */

    public function time()
    {
        return $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * Return location details by ip address.
     * 
     * @return  \Voyager\Util\Data\Collection
     */

    public function getLocation()
    {
        if(!$this->localhost())
        {
            $origin = $this->origin();
            
            if(!is_null($origin))
            {
                $parse = @json_decode(file_get_contents("http://geoplugin.net/json.gp?ip=" . $origin), true);

                return new Collection([
                    'status'                        => $parse['geoplugin_status'],
                    'success'                       => $parse['geoplugin_status'] === 200,
                    'country_code'                  => $parse['geoplugin_countryCode'],
                    'country'                       => $parse['geoplugin_countryName'],
                    'continent_code'                => $parse['geoplugin_continentCode'],
                    'continent'                     => $parse['geoplugin_continentName'],
                    'latitude'                      => $parse['geoplugin_latitude'],
                    'timezone'                      => $parse['geoplugin_timezone'],
                    'currency'                      => $parse['geoplugin_currencySymbol'],
                    'exchange_rate'                 => $parse['geoplugin_currencyConverter'],
                ]);
            }
        }
    }

}