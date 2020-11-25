<?php

namespace Voyager\Http\Route;

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
            'csrf'                  => false,
            'authentication'        => false,
            'cors'                  => false,
            'ajax'                  => false,
            'static'                => false,
            'cache'                 => null,
            'redirect'              => null,
            'expiration'            => null,
            'validation'            => null,
            'permission'            => [],
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
     * Set route method.
     * 
     * @param   mixed $verbs
     * @return  $this
     */

    public function methods($verbs)
    {
        $data = new Arr();

        if(is_string($verbs))
        {
            $data->push($verbs);
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
        return $this->set('middleware', $middleware);
    }

    /**
     * Set to maintenance mode.
     * 
     * @param   string $mode
     * @return  $this
     */

    public function mode(string $mode)
    {
        if(in_array($mode, ['up', 'down']))
        {
            return $this->set('mode', $mode);
        }
    }

    /**
     * Set maintenance mode off.
     * 
     * @return $this
     */

    public function up()
    {
        return $this->mode('up');
    }

    /**
     * Set maintenance mode.
     * 
     * @return $this
     */

    public function down()
    {
        return $this->mode('down');
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
     * @return  $this
     */

    public function locale(string $locale)
    {
        return $this->set('locale', $locale);
    }

    /**
     * Enable CSRF authentication.
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
     * Enable user authentication.
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
     * @param   bool $cors
     * @return  $this
     */

    public function cors(bool $cors = true)
    {
        $this->middlewares->push('cors');
        return $this->set('cors', $cors);
    }

    /**
     * Require request through ajax.
     * 
     * @param   bool $ajax
     * @param   
     */

    public function ajax(bool $ajax)
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
     * Set route expiration.
     * 
     * @param   string $datetime
     * @return  $this
     */

    public function expiration(string $datetime)
    {
        $this->middlewares->push('expire');
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
     * @param   string $user_type
     * @return  $this
     */

    public function permission(string $user_type)
    {
        $config = AuthConfig::get('users');
        $pool = new Arr($this->data->get('permission'));

        if(array_key_exists($user_type, $config))
        {
            $pool->push($config[$user_type]);
        }

        return $this->set('permission', $pool->get());
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
     * Grant permission to subadmin only.
     * 
     * @return  $this
     */

    public function subadmin()
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