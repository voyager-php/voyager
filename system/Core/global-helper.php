<?php

    use Voyager\Core\Application;
    use Voyager\Core\Cache;
    use Voyager\Facade\Str;
    use Voyager\Http\Security\Authentication;
    use Voyager\Resource\Locale\Lang;
    use Voyager\UI\View\TemplateEngine;
    use Voyager\Util\Data\Collection;
    use Voyager\Util\File\Reader;
    use Voyager\Util\Http\Session;
    use Voyager\Util\Str as Builder;

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
     * Return new instance of authentication class.
     * 
     * @return  \Voyager\Http\Security\Authentication
     */

    if(!function_exists('auth'))
    {
        function auth()
        {
            return new Authentication();
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
            return auth()->hasPermission($key);
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
            return auth()->userId();
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
            return auth()->get($key);
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
            return auth()->authenticated();
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
            return auth()->type() === 0;
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
            return auth()->type() === 1;
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
            return auth()->type() === 2;
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
            return auth()->type() === 3;
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
            return app()->request()->get($key, $default);
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
            return app()->request()->post($key, $default);
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
            $request = app()->request();
            $str = new Builder($request->https() ? 'https' : 'http');
            $str->append('://');
            $str->append(Str::moveFromEnd(strtolower(env('APP_URL')), '/'));

            if((int)$request->port() !== 80)
            {
                $str->append(':' . $request->port());
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
            ob_start();
            
            if(is_array($data))
            {
                var_export($data);
            }
            else
            {
                var_dump($data);
            }

            $content = ob_get_contents();
            ob_end_clean();

            app()->response = '<pre>' . str_replace(['&lt;?php&nbsp;', '?&gt;'], '', highlight_string('<?php ' . $content . ' ?>', true)) . '</pre>';
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
            $instance = new Cache($key);
            $cache = $instance->get();

            if(!is_null($value))
            {
                $instance->store($value);
                
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
                $lang = app()->locale ?? env('APP_LOCALE');
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
     * Return raw html string.
     * 
     * @param   string $path
     * @return  string
     */

    if(!function_exists('html'))
    {
        function html(string $path)
        {
            $file = new Reader($path);

            if($file->exist() && $file->is('html'))
            {
                return $file->content();
            }
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