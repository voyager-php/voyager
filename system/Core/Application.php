<?php

namespace Voyager\Core;

use Closure;
use Voyager\Facade\Str;
use Voyager\Http\Middleware\Kernel;
use Voyager\Http\Request;
use Voyager\Http\Response;
use Voyager\Http\Route\Router;
use Voyager\Http\Security\Authentication;
use Voyager\Util\Arr;
use Voyager\Util\Http\Session;

class Application extends Handler
{
    /**
     * Required PHP version.
     * 
     * @var string
     */

    private $minphp = '7.4';

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
     * @var array
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
     * Store http request object.
     * 
     * @var \Voyager\Http\Request
     */

    private $request;

    /**
     * Store app authentication object.
     * 
     * @var \Voyager\Http\Security\Authentication
     */

    private $authentication;

    /**
     * Class constructor.
     * 
     * @return void
     */

    public function __construct() {
        $this->setup();
        $this->request                  = new Request();
        $this->uri                      = $this->request->uri();
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
     * Return http request object.
     * 
     * @return  \Voyager\Http\Request
     */

    public function request()
    {
        return $this->request;
    }

    /**
     * Return app authentication object.
     * 
     * @return  \Voyager\Http\Security\Authentication
     */

    public function authentication()
    {
        return $this->authentication;
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
        if(is_null($this->route))
        {
            return [];
        }

        if(!is_null($key))
        {
            if(array_key_exists($key, $this->route))
            {
                return $this->route[$key];
            }
        }
        else
        {
            return $this->route;
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

            $uri = $this->uri;

            $this->phpversion = phpversion();
            $this->env = cache('env') ?? Env::get();
            $this->setInitialConfigurations();
            $this->route = cache($uri);
            $this->loadConstants();

            if(is_null($this->route))
            {
                $route = Router::init($uri)->getRoute();
                $this->route = $route;
                
                if(is_null($route))
                {
                    abort(404);
                }
                else
                {
                    $this->promise('cache_route', function() use($route, $uri) {
                        if(app()->cache)
                        {
                            cache($uri, $route);
                        }
                    });
                }
            }

            if($this->request()->get('_nojs', false) && !$this->request()->ajax() && !$this->route['ajax'])
            {
                $this->response = view('content.native.nojs');
                $this->terminate = true;
            }

            $this->overrideFromRouteData($this->route);

            $middleware = new Kernel($this->route);
            $response = $this->invokeController($this->route);

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
     * Invoke end point controller method and return response.
     * 
     * @param   array $route
     * @return  mixed
     */

    private function invokeController(array $route)
    {
        $controller = $route['controller'];
        
        if(!Str::startWith($controller, 'App\Controller'))
        {
            $controller = 'App\Controller\\' . str_replace('.', '\\', $controller);
        }

        $instance = new $controller($route);
        return $instance->getResponse();
    }

    /**
     * Override some app data provided by routes.
     * 
     * @param   array $route
     * @return  void
     */

    private function overrideFromRouteData(array $route)
    {
        $this->mode = $route['mode'];

        if(!is_null($route['redirect']))
        {
            $this->promise('redirection', function() use ($route) {
                app()->redirect = $route['redirect'];
            });
        }

        if(!is_null($route['timezone']))
        {
            $this->timezone = $route['timezone'];
        }

        if(!is_null($route['locale']))
        {
            $this->locale = $route['locale'];
        }
        else
        {
            $params = $this->request->get();

            if($params->has('_lang'))
            {
                $this->locale = $params->_lang;
            }
        }

        if(!is_null($route['backup_locale']))
        {
            $this->backup_locale = $route['backup_locale'];
        }

        if(!is_null($route['cache']))
        {
            $this->cache = $route['cache'];
        }
    }

    /**
     * Load constants and define them globally.
     * 
     * @return  void
     */

    private function loadConstants()
    {
        $constants = cache('constants');

        if(is_null($constants))
        {
            $constants = cache('constants', require path('config/constants.php'));
        }

        foreach($constants as $keyword => $value)
        {
            define($keyword, $value);
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
        $protocol = $this->request->protocol();
        
        if(!$this->success)
        {
            header($protocol . ' ' . $this->code . ' ' . lang('status.code.' . $this->code . '@statuscode'));
        }
        else
        {
            header($protocol . ' 200 OK');
        }

        if(!is_null($this->route))
        {
            if($this->route['cors'])
            {
                $this->header('Access-Control-Allow-Origin', '*');
            }
            else
            {
                $origin = $this->request->origin();
                
                if($origin !== '::1' && !$this->request->localhost())
                {
                    $this->header('Access-Control-Allow-Origin', $origin);
                    $this->header('Access-Control-Allow-Credentials', 'true');
                    $this->header('Access-Control-Max-Age', '86400');
                }
            }

            $this->header('Access-Control-Allow-Methods', strtoupper(implode(",", $this->route['verb'])));
        }

        if(!app()->cache)
        {
            $this->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $this->header('Pragma', 'no-cache');
            $this->header('Expires', '0');
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
        $this->mode             = env('APP_MODE');
        $this->locale           = env('APP_LOCALE');
        $this->backup_locale    = env('APP_BACKUP_LOCALE');
        $this->cache            = env('APP_CACHE');
        $this->limit            = env('APP_TIME_LIMIT');
        $this->timezone         = env('APP_TIMEZONE');
        $this->redirect         = env('APP_REDIRECT');
        $this->version          = env('API_VERSION');
        $this->authentication   = new Authentication();
    }

    /**
     * Detect if .env file returns no data.
     * 
     * @param   array $data
     * @return  void
     */

    protected function env(array $data)
    {
        if(empty($data))
        {
            $this->terminate = true;
        }
    }

    /**
     * Set application visibility mode.
     * 
     * @param   string $mode
     * @return  void
     */

    protected function mode(string $mode)
    {
        if(strtolower($mode) === 'down')
        {
            abort(503);
        }
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
        if(!is_null($response) && !$this->promises->hasKey('redirection'))
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
        if($this->minphp >= $version)
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