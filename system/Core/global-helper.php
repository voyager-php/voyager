<?php

    use Voyager\Core\Application;
    use Voyager\Core\Response;
    use Voyager\Database\DBResult;
    use Voyager\Facade\Auth;
    use Voyager\Facade\Cache;
    use Voyager\Facade\Request;
    use Voyager\Facade\Str;
    use Voyager\Resource\Locale\Lang;
    use Voyager\UI\View\TemplateEngine;
    use Voyager\Util\Data\Bundle;
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
     * Return if user has permission.
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
        function lang(string $id, string $lang = 'en')
        {
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
     * JSON DATA 
     * ----------------------------------
     * Return json string from array or
     * data collection.
     */

    if(!function_exists('json'))
    {
        function json($data = [], bool $paginate = false)
        {
            $meta = [];
            $is_collection = false;
            $code = app()->code;
            $message = $code === 200 && app()->locale === 'en' ? 'OK' : lang('status.code.' . $code . '@statuscode');
            
            if($data instanceof Collection)
            {
                $is_collection = true;
                app()->contentType = 'application/json';
                $array = $data->toArray();
            }
            else if($data instanceof Bundle || $data instanceof DBResult)
            {
                app()->contentType = 'application/json';
                $array = $data->toArray();
            }
            else if($data instanceof Response)
            {
                app()->contentType = 'application/json';
                $array = $data->output();
            }
            else if(is_array($data))
            {
                app()->contentType = 'application/json';
                $array = $data;
            }
            else
            {
                return $data;
            }

            // Set meta informations.

            if(!empty($array) && !$is_collection && !$is_collection && count(array_filter(array_keys($array), 'is_string')) === 0)
            {
                $total = sizeof($array);

                $meta['total']      = $total;
                $meta['paginate']   = $paginate;

                // Add some pagination properties and limit
                // the result by how many rows per page.

                if($paginate)
                {
                    $page = (int)Request::get('page', 1);
                    $per_page = (int)Request::get('per_page', 10);
                    $total_page = ceil($total / ($page * $per_page));

                    $rows = [];

                    $start = ($page * $per_page) - $per_page;
                    $end = $start + $per_page;
                    $num = 0;

                    for($i = $start + 1; $i <= $end; $i++)
                    {
                        if($i <= $total)
                        {
                            $num++;
                            $rows[] = $array[$i - 1];
                        }
                    }

                    $array = $rows;

                    $meta['page']  = $page;
                    $meta['per_page'] = $per_page;
                    $meta['total_page'] = $total_page;
                    $meta['rows'] = $num;
                }
            }

            // Return json formatted response.

            return json_encode([
                'code'       => $code,
                'success'    => $code === 200,
                'status'     => $message,
                'meta'       => $meta,
                'data'       => $array,
            ]);
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