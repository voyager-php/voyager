<?php

namespace Voyager\Core;

use Closure;
use Voyager\Facade\Cache;
use Voyager\Facade\Request;
use Voyager\Facade\Str;
use Voyager\Http\Middleware\Kernel;
use Voyager\Http\Response;
use Voyager\Http\Route\Router;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\Http\Session;

class Application
{
    /**
     * Required PHP version.
     * 
     * @var string
     */

    private $minphp = '7.4.8';

    /**
     * Current framework version.
     * 
     * @var string
     */

    private $version;

    /**
     * Application instance object.
     * 
     * @var object
     */

    private static $context;

    /**
     * Indicate if applicaion is already instantiated.
     * 
     * @var bool
     */

    private static $instantiated = false;

    /**
     * Indicate if application was already started.
     * 
     * @var bool
     */

    protected $started = false;

    /**
     * Indicate if application was already running.
     * 
     * @var bool
     */

    protected $running = false;

    /**
     * Indicate if application was ended.
     * 
     * @var bool
     */

    protected $ended = false;

    /**
     * Store reactive data.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $data;

    /**
     * Store route data.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private $route;

    /**
     * Store request headers.
     * 
     * @var \Voyager\Util\Arr
     */

    private $headers;

    /**
     * Store the current request uri.
     * 
     * @var string
     */

    private $uri;

    /**
     * Store end request promise callbacks.
     * 
     * @var \Voyager\Util\Arr
     */

    private $promises;

    /**
     * Class constructor.
     * 
     * @return void
     */

    public function __construct() {
        $this->uri                      = Request::uri();
        $this->data                     = new Arr();
        $this->promises                 = new Arr();
        $this->headers                  = new Arr();
    }

    /**
     * Return current API version.
     * 
     * @return string
     */

    public function version()
    {
        return $this->version;
    }

    /**
     * Register http headers.
     * 
     * @param   string $name
     * @param   string $value
     * @return  void
     */

    public function header(string $name, string $value)
    {
        if(!$this->headers->hasKey($name))
        {
            $this->headers->set($name, $value);
        }
    }

    /**
     * Dynamically return reactive data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function __get(string $key)
    {
        if($this->data->hasKey($key))
        {
            return $this->data->get($key);
        }
    }

    /**
     * Set or update reactive data and call the binded method.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  void
     */

    public function __set(string $key, $value)
    {
        $curr = $this->{$key};

        if($curr !== $value)
        {
            $this->data->set($key, $value);

            if(method_exists($this, $key))
            {
                $this->{$key}($value);
            }
        }
    }

    /**
     * Add new end request promise callbacks.
     * 
     * @param   string $key
     * @param   \Closure $promise
     * @return  void
     */

    public function promise(string $key, Closure $promise)
    {
        $this->promises->set($key, $promise);
    }

    /**
     * Return current route data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function route(string $key = null)
    {
        if(!is_null($key))
        {
            if($this->route->has($key))
            {
                return $this->route->{$key};
            }
        }
        else
        {
            return $this->route->toArray();
        }
    }

    /**
     * The voyager magic happens here.
     * 
     * @return  void
     */

    private function runtime()
    {
        if(!$this->running)
        {
            require 'global-helper.php';

            $this->env = new Collection(Env::get() ?? []);
            
            if($this->env->empty())
            {
                $this->terminate = true;
            }

            $this->setInitialConfigurations();
            $this->phpversion = phpversion();
            $this->route = new Collection(Cache::get($this->uri) ?? []);

            if($this->route->empty())
            {
                $this->route = Router::init($this->uri)->getRoute();

                $route = $this->route->toArray();
                $uri = $this->uri;
                
                $this->promise('cache_route', function() use($route, $uri) {
                    if(is_null(Cache::get($uri)) && app()->cache)
                    {
                        Cache::store($uri, $route);
                    }
                });
                
                if($this->route->empty())
                {
                    abort(404);
                }
            }

            if(!is_null($this->route->redirect))
            {
                $destination = $this->route->redirect;

                $this->promise('redirection', function() use ($destination) {
                    app()->redirect = $destination;
                });
            }

            if(!is_null($this->route->timezone))
            {
                $this->timezone = $this->route->timezone;
            }

            if(!is_null($this->route->locale))
            {
                $this->locale = $this->route->locale;
            }
            else
            {
                $params = Request::get();
                if($params->has('_lang'))
                {
                    $this->locale = $params->_lang;
                }
            }

            if(!is_null($this->route->cache))
            {
                $this->cache = $this->route->cache;
            }

            $this->static_page = $this->route->static;

            new Kernel($this->route);

            $controller = $this->route->controller;

            if(!Str::startWith($controller, 'App\Controller'))
            {
                $controller = 'App\Controller\\' . str_replace('.', '\\', $controller);
            }

            $instance = new $controller($this->route);
            $response = $instance->getResponse();

            if(is_null($response))
            {
                abort(204);
            }

            $this->setHeaders();
            $this->response = $response;
            $this->terminate = true;
        }
    }

    /**
     * Set http headers.
     * 
     * @return void
     */

    public function setHeaders()
    {
        http_response_code($this->code);
        $protocol = Request::protocol();
        
        if(!$this->success)
        {
            header($protocol . ' ' . $this->code . ' ' . lang('status.code.' . $this->code . '@statuscode'));
        }
        else
        {
            header($protocol . ' 200 OK');
        }

        if(!$this->route->empty())
        {
            $this->header('Access-Control-Allow-Methods', strtoupper(implode(",", $this->route->verb)));
            
            if($this->route->cors)
            {
                $this->header('Access-Control-Allow-Origin', '*');
            }
            else
            {
                $origin = Request::origin();
                
                if($origin !== '::1' && !Request::localhost())
                {
                    $this->header('Access-Control-Allow-Origin', $origin);
                    $this->header('Access-Control-Allow-Credentials', 'true');
                    $this->header('Access-Control-Max-Age', '86400');
                }
            }
        }

        if(!$this->headers->empty())
        {
            $this->headers->each(function(string $key, $value) {
                header($key . ': ' . $value);
            });
        }
    }

    /**
     * Set initial configurations provided by .env file.
     * 
     * @return  void
     */

    private function setInitialConfigurations()
    {
        $this->debug            = env('APP_DEBUG');
        $this->code             = 200;
        $this->success          = true;
        $this->contentType      = null;
        $this->response         = null;
        $this->terminate        = false;
        $this->proceed          = false;
        $this->index            = 0;
        $this->bypass           = false;
        $this->locale           = env('APP_LOCALE');
        $this->cache            = env('APP_CACHE');
        $this->limit            = env('APP_TIME_LIMIT');
        $this->timezone         = env('APP_TIMEZONE');
        $this->redirect         = env('APP_REDIRECT');
        $this->version          = env('API_VERSION');
    }

    /**
     * Set http status code.
     * 
     * @param   bool $code
     * @return  void
     */

    protected function code(int $code)
    {
        if($code !== 200)
        {
            $this->success = false;
            $this->promise('error', function() {
                $sess = new Session('errors');

                if(!$sess->has('logs'))
                {
                    $sess->set('logs', []);
                }

                $data = $sess->get('logs');
                $data[] = time();

                $sess->set('logs', $data);
            });
        }
        else
        {
            $this->success = true;
            $this->promises->remove('error');
        }
    }

    /**
     * Set header content-type.
     * 
     * @param   string $type
     * @return  void
     */

    protected function contentType(string $type = 'text/html')
    {
        header('Content-Type: ' . $type);
    }

    /**
     * Set execution time limit.
     * 
     * @param   int $limit
     * @return  void
     */

    protected function limit(int $limit)
    {
        set_time_limit($limit);
    }

    /**
     * Set application debug mode.
     * 
     * @param   bool $mode
     * @return  void
     */

    protected function debug(bool $mode)
    {
        if(!$mode)
        {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
        else
        {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    /**
     * Set application timezone.
     * 
     * @param   string $timezone
     * @return  void
     */

    protected function timezone(string $timezone)
    {
        date_default_timezone_set($timezone);
    }

    /**
     * Redirect application.
     * 
     * @param   string $destination
     * @return  void
     */

    protected function redirect(string $destination = null)
    {
        if(!is_null($destination))
        {
            $this->callPromises();
            header('Location: ' . $destination, true);
            exit;
        }
    }

    /**
     * Set response to request.
     * 
     * @param   mixed $response
     * @return  void
     */

    protected function response($response = null)
    {
        if(!is_null($response))
        {
            $response = new Response($response);
            $this->contentType = $response->contentType();
            echo $response->result();
        }
    }

    /**
     * Validate PHP version.
     * 
     * @param   string $version
     * @return  void
     */

    protected function phpversion(string $version)
    {
        if($this->minphp < $version)
        {
            $this->response = 'Voyager require PHP version ' . $this->minphp . ' or higher.';
            $this->terminate = true;
        }
    }

    /**
     * Terminate application.
     * 
     * @param   bool $terminate
     * @return  void
     */

    protected function terminate(bool $terminate)
    {
        if($terminate)
        {
            $this->running = true;
            $this->end();
        }
    }

    /**
     * Start application.
     * 
     * @return  void
     */

    public function start()
    {
        if(!$this->started && static::$instantiated)
        {
            $this->started = true;
            $this->runtime();
        }
    }

    /**
     * Terminate application.
     * 
     * @return  void
     */

    public function end()
    {
        if($this->running && !$this->ended)
        {
            $this->callPromises();
            $this->ended = true;
            exit(0);
        }
    }

    /**
     * Call application promises.
     * 
     * @return  void
     */

    public function callPromises()
    {
        $this->promises->each(function(string $key, Closure $callback) {
            $callback();
        });
    }

    /**
     * Instantiate application.
     * 
     * @return  \Voyager\Core\Application
     */

    public static function init()
    {
        if(!static::$instantiated && is_null(static::$context))
        {
            static::$instantiated = true;
            static::$context = new self();

            return static::$context;
        }
    }

    /**
     * Return application instance object.
     * 
     * @return  \Voyager\Core\Application
     */

    public static function context()
    {
        return static::$context;
    }
}