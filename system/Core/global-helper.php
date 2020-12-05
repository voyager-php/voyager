<?php

    use Voyager\Core\Application;
    use Voyager\Facade\Auth;
    use Voyager\Facade\Cache;
    use Voyager\Facade\Request;
    use Voyager\Facade\Str;
    use Voyager\Resource\Locale\Lang;
    use Voyager\UI\View\TemplateEngine;
    use Voyager\Util\Data\Collection;
    use Voyager\Util\Http\Session;
    use Voyager\Util\Str as Builder;

    /**
     * Check if variable is a closure.
     * 
     * @param   mixed $closure
     * @return  bool
     */

    if(!function_exists('is_closure'))
    {
        function is_closure($closure)
        {
            return $closure instanceof Closure;
        }
    }

    /**
     * Return application context.
     * 
     * @return  \Voyager\Core\Application
     */

    if(!function_exists('app'))
    {
        function app()
        {
            return Application::context();
        }
    }

    /**
     * Format path string.
     * 
     * @param   string $path
     * @return  string
     */

    if(!function_exists('path'))
    {
        function path(string $path)
        {
            if(!Str::startWith($path, '../'))
            {
                $path = '../' . $path;
            }

            return $path;
        }
    }

    /**
     * Return environment data.
     * 
     * @param   string $key
     * @param   mixed $default
     * @return  mixed
     */

    if(!function_exists('env'))
    {
        function env(string $key, $default = null)
        {
            $env = app()->env;

            return $env->has($key) ? $env->{$key} : $default;
        }
    }

    /**
     * Return app name declared from the .env file.
     * 
     * @return  string
     */

    if(!function_exists('app_name'))
    {
        function app_name()
        {
            return env('INFO_NAME');
        }
    }

    /**
     * Return app description from the .env file.
     * 
     * @return  string
     */

    if(!function_exists('app_description'))
    {
        function app_description()
        {
            return env('INFO_DESCRIPTION');
        }
    }

    /**
     * Return app version from the .env file.
     * 
     * @return  string
     */

    if(!function_exists('app_version'))
    {
        function app_version()
        {
            return env('INFO_VERSION');
        }
    }

    /**
     * Return generated csrf token.
     * 
     * @return  string
     */

    if(!function_exists('csrf_token'))
    {
        function csrf_token()
        {
            $sess = new Session('csrf', [
                'token' => Str::hash(time()),
            ]);

            return $sess->has('token') ? $sess->get('token') : null;
        }
    }

    /**
     * Return true if user has permission.
     * 
     * @param   string $key
     * @return  bool
     */

    if(!function_exists('permission'))
    {
        function permission(string $key)
        {
            return Auth::hasPermission($key);
        }
    }

    /**
     * Return the current user id.
     * 
     * @return  mixed
     */

    if(!function_exists('user_id'))
    {
        function user_id()
        {
            return Auth::userId();
        }
    }

    /**
     * Get authentication data.
     * 
     * @param   string $key
     * @return  mixed
     */

    if(!function_exists('auth'))
    {
        function auth(string $key)
        {
            return Auth::get($key);
        }
    }

    /**
     * Return true if current session is authenticated.
     * 
     * @return  bool
     */

    if(!function_exists('authenticated'))
    {
        function authenticated()
        {
            return Auth::authenticated();
        }
    }

    /**
     * Return true if current session is superadmin.
     * 
     * @return  bool
     */

    if(!function_exists('is_superadmin'))
    {
        function is_superadmin()
        {
            return Auth::type() === 0;
        }
    }

    /**
     * Return true if current session is admin.
     * 
     * @return  bool
     */

    if(!function_exists('is_admin'))
    {
        function is_admin()
        {
            return Auth::type() === 1;
        }
    }

    /**
     * Return true if current session is member.
     * 
     * @return  bool
     */

    if(!function_exists('is_member'))
    {
        function is_member()
        {
            return Auth::type() === 2;
        }
    }

    /**
     * Return true if current session is just a guest.
     * 
     * @return  bool
     */

    if(!function_exists('is_guest'))
    {
        function is_guest()
        {
            return Auth::type() === 3;
        }
    }

    /**
     * Return GET parameter value.
     * 
     * @param   string $key
     * @param   mixed $default
     * @return  mixed
     */

    if(!function_exists('get'))
    {
        function get(string $key, $default = null)
        {
            return Request::get($key, $default);
        }
    }

    /**
     * Return POST parameter value.
     * 
     * @param   string $key
     * @param   mixed $default
     * @return  mixed
     */

    if(!function_exists('post'))
    {
        function post(string $key, $default = null)
        {
            return Request::post($key, $default);
        }
    }

    /**
     * Generate a valid URL.
     * 
     * @param   string $uri
     * @param   array $param
     * @param   bool $encode
     * @return  string
     */

    if(!function_exists('url'))
    {
        function url(string $uri, array $param = null, bool $encode = true)
        {
            $str = new Builder(Request::https() ? 'https' : 'http');
            $str->append('://');
            $str->append(Str::moveFromEnd(strtolower(env('APP_URL')), '/'));

            if(Request::port() !== 80)
            {
                $str->append(':' . Request::port());
            }

            $str->append('/' . Str::moveFromBothEnds(strtolower($uri), '/'));

            if(!is_null($param))
            {
                if($str->has('?'))
                {
                    $str->append($str->break('?')[0]);
                }

                $str->append('?');

                foreach($param as $key => $value)
                {
                    $str->append($key . '=' . ($encode ? urlencode($value) : $value) . '&');
                }

                $str->move(0, 1);
            }

            return $str->get();
        }
    }

    /**
     * Terminate application and dump data.
     * 
     * @param   mixed $data
     * @return  void
     */

    if(!function_exists('dd'))
    {
        function dd($data)
        {
            app()->response = $data;
            app()->terminate = true;
        }
    }

    /**
     * Set or return cache data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  mixed
     */

    if(!function_exists('cache'))
    {
        function cache(string $key, $value = null)
        {
            $cache = Cache::get($key);

            if(!is_null($value))
            {
                Cache::store($key, $value);
                
                return $value;
            }
            else
            {
                return $cache;
            }
        }
    }

    /**
     * Return localized translation.
     * 
     * @param   string $id
     * @param   string $lang
     * @return  string
     */

    if(!function_exists('lang'))
    {
        function lang(string $id, string $lang = null)
        {
            if(is_null($lang))
            {
                $lang = app()->route('locale') ?? env('APP_LOCALE');
            }

            $instance = new Lang($id);
            return $instance->get($lang);
        }
    }

    /**
     * Return frontend view string.
     * 
     * @param   string $content_path
     * @param   array $emit
     * @return  string
     */

    if(!function_exists('view'))
    {
        function view(string $path, array $emit = [])
        {
            $view = new TemplateEngine($path, $emit);
            return $view->output();
        }
    }

    /**
     * Abort request and respond with an error.
     * 
     * @param   int $code
     * @param   mixed $data
     * @return  void
     */

    if(!function_exists('abort'))
    {
        function abort(int $code = 500, $data = null)
        {
            app()->code = $code;
            app()->setHeaders();

            if(is_null($data))
            {
                $instance = new App\Controller\ErrorStatusController(new Collection(['method' => 'index']));
                app()->response = $instance->getResponse();
            }
            else
            {
                app()->response = $data;
            }

            app()->terminate = true;
        }
    }

    /**
     * Redirect to other location.
     * 
     * @param   string $url
     * @param   bool $permanent
     */

    if(!function_exists('redirect'))
    {
        function redirect(string $url, bool $permanent = false)
        {
            app()->code = $permanent ? 308 : 307;
            app()->redirect = $url;
        }
    }