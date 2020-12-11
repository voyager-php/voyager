<?php

namespace Voyager\Http\Route;

use Voyager\Facade\Str;
use Voyager\Http\Security\AuthConfig;
use Voyager\Util\Arr;

abstract class RouteBase
{
    /**
     * Store route data.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $data;

    /**
     * Store middlewares.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $middlewares;

    /**
     * Store middleware key of a route property.
     * 
     * @var array
     */

    protected $middleware_keys = [
        'csrf'              => 'csrf',
        'cors'              => 'cors',
        'ip'                => 'ip',
        'ajax'              => 'ajax',
        'accessibility'     => 'accessibility',
        'expiration'        => 'accessibility',
        'validation'        => 'validation',
        'location'          => 'location',
    ];

    /**
     * Set route data.
     * 
     * @return void
     */

    protected function setRouteData()
    {
        $this->middlewares = new Arr();
        $this->data = new Arr([
            'name'                  => null,
            'verb'                  => [],
            'uri'                   => null,
            'controller'            => null,
            'method'                => 'index',
            'mode'                  => 'up',
            'middleware'            => 'web',
            'middlewares'           => [],
            'timezone'              => null,
            'locale'                => null,
            'backup_locale'         => null,
            'csrf'                  => false,
            'authentication'        => false,
            'cors'                  => false,
            'ip'                    => false,
            'ajax'                  => false,
            'static'                => false,
            'cache'                 => null,
            'redirect'              => null,
            'expiration'            => null,
            'accessibility'         => null,
            'validation'            => null,
            'permission'            => [],
            'location'              => [],
            'limit'                 => 60,
            'period'                => 60,
        ]);
    }

    /**
     * Set route data value.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function set(string $key, $value)
    {
        $this->data->set($key, $value);
        return $this;
    }

    /**
     * Set route name.
     * 
     * @param   string $name
     * @return  $this
     */

    public function name(string $name)
    {
        return $this->set('name', $name);
    }

    /**
     * Set route controller class.
     * 
     * @param   string $controller
     * @return  $this
     */

    public function controller(string $controller)
    {
        return $this->set('controller', $controller);
    }

    /**
     * Set route controller method.
     * 
     * @param   string $method
     * @return  $this
     */

    public function method(string $method)
    {
        return $this->set('method', $method);
    }

    /**
     * Set route request method or http verbs.
     * 
     * @param   mixed $verbs
     * @return  $this
     */

    public function verb($verbs)
    {
        $data = new Arr();

        if(is_string($verbs))
        {
            foreach(explode(',', $verbs) as $value)
            {
                if($value !== '')
                {
                    $data->push($value);
                }
            }
        }
        else if(is_array($verbs))
        {
            $data->merge($verbs);
        }

        return $this->set('verb', $data->get());
    }

    /**
     * Set middleware group alias.
     * 
     * @param   string $middleware
     * @return  $this
     */

    public function middleware(string $middleware)
    {
        if(Str::startWith($middleware, 'App\Middleware'))
        {
            $this->middlewares->push($middleware);

            return $this;
        }
        else
        {
            return $this->set('middleware', $middleware);
        }
    }

    /**
     * Set maintenance mode off.
     * 
     * @return $this
     */

    public function up()
    {
        return $this->set('mode', 'up');
    }

    /**
     * Set maintenance mode.
     * 
     * @return $this
     */

    public function down()
    {
        return $this->set('mode', 'down');
    }

    /**
     * Set request timezone.
     * 
     * @param   string $timezone
     * @return  $this
     */

    public function timezone(string $timezone)
    {
        return $this->set('timezone', $timezone);
    }

    /**
     * Set locale translation.
     * 
     * @param   string $locale
     * @param   string $backup
     * @return  $this
     */

    public function locale(string $locale, string $backup = null)
    {
        if(!is_null($backup))
        {
            $this->set('backup_locale', $backup);
        }

        return $this->set('locale', $locale);
    }

    /**
     * Enable CSRF validation.
     * 
     * @param   bool $csrf
     * @return  $this
     */

    public function csrf(bool $csrf = true)
    {
        $this->middlewares->push('csrf');
        return $this->set('csrf', $csrf);
    }

    /**
     * Require user authentication.
     * 
     * @param   bool $auth
     * @return  $this
     */

    public function auth(bool $auth = true)
    {
        return $this->set('authentication', $auth);
    }

    /**
     * Enable cross-origin request.
     * 
     * @param   bool $enable
     * @return  $this
     */

    public function cors(bool $enable = true)
    {
        $this->middlewares->push('cors');
        return $this->set('cors', $enable);
    }

    /**
     * Enable IP address validation in middleware.
     * 
     * @param   bool $enable
     * @return  $this
     */

    public function validateIP(bool $enable = true)
    {
        if($enable)
        {
            $this->middlewares->push('ip');
        }

        return $this->set('ip', $enable);
    }

    /**
     * Require request through ajax or XMLHttpRequest.
     * 
     * @param   bool $ajax
     * @param   
     */

    public function ajax(bool $ajax = true)
    {
        $this->middlewares->push('ajax');
        return $this->set('ajax', $ajax);
    }

    /**
     * Redirect request every after request.
     * 
     * @param   string $uri
     * @return  $this
     */

    public function redirect(string $uri)
    {
        return $this->set('redirect', $uri);
    }

    /**
     * Set date and time when route will be accessible.
     * 
     * @param   string $datetime
     * @return  $this
     */

    public function opens(string $datetime)
    {
        $this->middlewares->push('accessibility');
        return $this->set('accessibility', $datetime);
    }

    /**
     * Set datetime when route will expire.
     * 
     * @param   string $datetime
     * @return  $this
     */

    public function closes(string $datetime)
    {
        $this->middlewares->push('accessibility');
        return $this->set('expiration', $datetime);
    }

    /**
     * Set validation class.
     * 
     * @param   string $validate
     * @return  $this
     */

    public function validate(string $validate)
    {
        $this->middlewares->push('validation');
        return $this->set('validation', $validate);
    }

    /**
     * Set request rate limiter value.
     * 
     * @param   int $limit
     * @param   int $period
     * @return  $this
     */

    public function limit(int $limit, int $period = 60)
    {
        return $this->set('limit', $limit)
                    ->set('period', $period);
    }

    /**
     * Disable caching in route.
     * 
     * @param   bool $cache
     * @return  $this
     */

    public function cache(bool $cache = true)
    {
        return $this->set('cache', $cache);
    }

    /**
     * Set route to static page.
     * 
     * @param   bool $static
     * @return  $this
     */

    public function static(bool $static = true)
    {
        return $this->set('static', $static);
    }

    /**
     * Grant permission to users.
     * 
     * @param   mixed $type
     * @return  $this
     */

    public function permission($type)
    {
        $config = AuthConfig::get('users');
        $pool = $this->data->get('permission');

        if(is_string($type))
        {
            if(array_key_exists($type, $config))
            {
                $key = $config[$type];

                if(!in_array($key, $pool))
                {
                    $pool[] = $key;
                }
            }
        }
        else if(is_array($type))
        {
            foreach($type as $item)
            {
                if(array_key_exists($item, $config))
                {
                    $key = $config[$item];

                    if(!in_array($key, $pool))
                    {
                        $pool[] = $key;
                    }
                }
            }
        }

        if(!empty($pool))
        {
            $this->middlewares->push('permission');
        }

        return $this->set('permission', $pool);
    }

    /**
     * Grant permission to superadmin only.
     * 
     * @return $this
     */

    public function superadmin()
    {
        return $this->permission('superadmin');
    }

    /**
     * Grant permission to admin only.
     * 
     * @return  $this
     */

    public function admin()
    {
        return $this->permission('admin');
    }

    /**
     * Grant permission to member only.
     * 
     * @return  $this
     */

    public function member()
    {
        return $this->permission('member');
    }

    /**
     * Grant permission to guest only.
     * 
     * @return  $this
     */

    public function guest()
    {
        return $this->permission('guest');
    }

    /**
     * Block access from countries.
     * 
     * @param   mixed $countries
     * @return  $this
     */

    public function blockCountries($countries)
    {
        $this->middlewares->push('location');

        if(is_string($countries))
        {
            foreach(explode(',', $countries) as $country)
            {
                if($country !== '')
                {
                    $countries[] = $country;
                }
            }
        }

        return $this->set('location', $countries);
    }
    
    /**
     * Return route data.
     * 
     * @return  array
     */

    public function data()
    {
        $this->set('middlewares', $this->middlewares->get());
        return $this->data->get();
    }

}