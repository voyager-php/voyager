<?php

namespace Voyager\UI\View;

use Voyager\Facade\Auth;
use Voyager\Facade\Dir;
use Voyager\Facade\File;
use Voyager\Facade\Request;
use Voyager\Facade\Str;
use Voyager\UI\Components\Renderer;
use Voyager\UI\Monsoon\Config;
use Voyager\UI\Monsoon\CSSUtility;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
use Voyager\Util\File\Directory;
use Voyager\Util\File\Reader;
use Voyager\Util\Str as Builder;

class TemplateEngine
{
    /**
     * View resources file extension.
     * 
     * @var string
     */

    private static $ext = '.html';

    /**
     * View resource path.
     * 
     * @var string
     */

    private static $path = 'resource/view/';

    /**
     * Store hashed filename for resources.
     * 
     * @var string
     */

    private static $hash;

    /**
     * Content resource string.
     * 
     * @var string
     */

    private $content;

    /**
     * HTML output string.
     * 
     * @var string
     */

    private $output = '';

    /**
     * Store emitted data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $emit;

    /**
     * Current request uri.
     * 
     * @var string
     */

    private $uri;

    /**
     * Store javascript strings.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $script;

    /**
     * Store stylesheet strings.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $styles;

    /**
     * Create new template instance.
     * 
     * @param   string $content
     * @param   array $emit
     */

    public function __construct(string $content, array $emit = [])
    {
        $this->uri = Request::uri();
        $this->content = $content;
        $this->emit = new Arr($emit);

        if(is_null(static::$script))
        {
            static::$script = new Arr();
        }

        if(is_null(static::$styles))
        {
            static::$styles = new Arr();
        }

        if(is_null(static::$hash))
        {
            static::$hash = Str::hash(app()->success ? $this->uri : app()->code);
        }

        $cache = new Reader(static::cachePath(Str::hash($this->uri)));
        
        if(app()->success && $cache->exist())
        {
            $this->output = $cache->content();
        }
        else
        {
            $render = true;
            
            if(!app()->success && app()->cache)
            {
                $errordoc = new Reader(static::cachePath(Str::hash(app()->code)));
                
                if($errordoc->exist())
                {
                    $this->output = $errordoc->content();
                    $render = false;
                }
            }

            if($render)
            {
                $html = new Parser($this->extendLayout($this->loadContent()));
                $html = static::compile($html->get(), $this->emit);

                if(Str::has($html, '<v-') && Str::has($html, '</v-'))
                {
                    $html = Renderer::start($html);
                }

                if(Config::get('enable'))
                {
                    $html = CSSUtility::extract($html);
                }

                $this->output = $this->makeExternalResource($html);
            }
        }
    }

    /**
     * Return cache file path.
     * 
     * @param   string $path
     * @return  string
     */

    public static function cachePath(string $path)
    {
        return 'storage/cache/html/' . $path . static::$ext;
    }

    /**
     * Return view resource path.
     * 
     * @param   string $path
     * @return  string
     */

    public static function resourcePath(string $path)
    {
        return static::$path . $path . static::$ext;
    }

    /**
     * Load the generated PHP file and return html string.
     * 
     * @param   string $html
     * @param   \Voyager\Util\Arr $emit
     * @return  string
     */

    public static function compile(string $_html, Arr $_emit)
    {
        foreach($_emit->get() as $_key => $_value)
        {
            ${$_key} = $_value;
        }

        unset($_key);
        unset($_value);
        unset($_emit);

        ob_start();
        eval('?>' . $_html);
        $_content = ob_get_contents();
        ob_end_clean();

        return str_replace('" >', '">', $_content);
    }

    /**
     * Generate external css and js files.
     * 
     * @param   string $html
     * @return  string
     */

    private function makeExternalResource(string $html)
    {
        $hash = static::$hash;
        
        if(Str::has($html, '</head>'))
        {
            $ext = '.css';
            $path = 'public/css/static/';
            $break = Str::break($html, '</head>');
            $exist = File::exist($path . $hash . $ext);

            $app_css = new Reader('resource/css/app.css');

            if($app_css->exist())
            {
                static::addStylesheet('app', $app_css->content());
            }

            if(!(app()->cache && $exist) && !static::$styles->empty())
            {
                if(!Dir::exist('public/css/'))
                {
                    $dir = new Directory('public/');
                    $dir->makeFolder('css')
                        ->makeFolder('static');
                }

                if(!app()->cache || (app()->cache && $exist))
                {
                    File::delete($path . $hash . $ext);
                }

                $source = static::$styles->reverse()->join();
                
                if(env('APP_COMPRESS'))
                {
                    $source = preg_replace( "/\r|\n/", "", $source);
                }

                Dir::make($path, $hash . $ext, $source);
                $exist = true;
            }

            if($exist)
            {
                $html = $break[0] . '<link rel="stylesheet" type="text/css" href="' . url('css/static/' . $hash . $ext) . '"></head>' . $break[1];
            }
        }
        
        if(Str::has($html, '</body>'))
        {
            $ext = '.js';
            $path = 'public/js/static/';
            $break = Str::break($html, '</body>');
            $exist = File::exist($path . $hash . $ext);

            ScriptResourceProvider::init();

            $app_js = new Reader('resource/js/app.js');

            if($app_js->exist())
            {
                static::addScript('app', $app_js->content());
            }

            if(!(app()->cache && $exist) && !static::$script->empty())
            {
                if(!Dir::exist('public/js/'))
                {
                    $dir = new Directory('public/');
                    $dir->makeFolder('js')
                        ->makeFolder('static');
                }

                if(!app()->cache || (app()->cache && $exist))
                {
                    File::delete($path . $hash . $ext);
                }

                $source = static::$script->reverse()->join();

                if(env('APP_COMPRESS'))
                {
                    $source = preg_replace( "/\r|\n/", "", $source);
                }

                Dir::make($path, $hash . $ext, $source);
                $exist = true;
            }

            if($exist)
            {
                $builder = new Builder('const app={');

                $builder->append('authenticated: ' . (Auth::authenticated() ? 'true' : 'false') . ',');
                $builder->append('authID: "' . Auth::id() . '",');

                $builder->append('get: function(){return JSON.parse("')
                        ->append(addslashes(Request::get()->toJson()))
                        ->append('");},');

                $builder->append('post: function(){return JSON.parse("')
                        ->append(addslashes(Request::post()->toJson()))
                        ->append('");},');

                $builder->append('resource: function(){return JSON.parse("')
                        ->append(addslashes(json_encode(app()->route('resource'))))
                        ->append('");},');

                $builder->append('token:"')
                        ->append(csrf_token())
                        ->append('"');

                $builder->append('};');

                $html = $break[0] . '<script type="text/javascript">' . $builder->get() . '</script><script type="text/javascript" src="' . url('js/static/' . $hash . $ext) . '"></script></body>' . $break[1];
            }
        }

        if(app()->static_page)
        {
            Dir::make('storage/cache/html/', $hash . '.html', $html);
        }

        return $html;
    }

    /**
     * Wrap content with extended layout.
     * 
     * @param   \Voyager\Util\Data\Collection $data
     * @return  string
     */

    private function extendLayout(Collection $data)
    {
        if(!is_null($data->layout))
        {
            $reader = new Reader(static::resourcePath('layout/' . str_replace('.', '/', $data->layout)));
            
            if($reader->exist())
            {
                if(!is_null($data->styles))
                {
                    static::addStylesheet('layout', $data->styles);
                }

                if(!is_null($data->script))
                {
                    static::addScript('layout', $data->script);
                }

                return str_replace('@content', $data->content, $reader->content());
            }
        }

        return $data->content;
    }

    /**
     * Load html content.
     * 
     * @return \Voyager\Util\Data\Collection
     */

    private function loadContent()
    {
        $reader = new Reader(static::resourcePath(str_replace('.', '/', $this->content)));

        if($reader->exist())
        {
            $html = $reader->content();
            $content = Parser::template($html);
            $script = Parser::script($html);
            $styles = Parser::style($html);
            $attribute = Parser::getAttribute('template', $html);

            if(!$this->emit->hasKey('title'))
            {
                $this->emit->set('title', $attribute->title ?? 'Untitled Page');
            }

            return new Collection([
                'content'    => $content,
                'layout'     => $attribute->extend ?? null,
                'script'     => $script,
                'styles'     => $styles,
            ]);
        }
    }

    /**
     * Stash javascript to be compiled later on.
     * 
     * @param   string $key
     * @param   string $script
     * @return  void
     */

    public static function addScript(string $key, string $script)
    {
        $hash = Str::hash($key, 'md5');
        if(!static::$script->hasKey($hash))
        {
            static::$script->set($hash, static::stripBlockComments($script));
        }
    }

    /**
     * Stash css to be compiled later on.
     * 
     * @param   string $key
     * @param   string $styles
     * @return  void
     */

    public static function addStylesheet(string $key, string $styles)
    {
        $hash = Str::hash($key, 'md5');
        if(!static::$styles->hasKey($hash))
        {
            static::$styles->set($hash, static::stripBlockComments($styles));
        }
    }

    /**
     * Remove all block comments from string.
     * 
     * @param   string $string
     * @return  string
     */

    private static function stripBlockComments(string $string)
    {
        $str = new Builder();

        foreach(explode('/**', $string) as $segment)
        {
            if(Str::has($segment, '*/'))
            {
                $str->append(Str::break($segment, '*/')[1]);
            }
            else
            {
                $str->append($segment);
            }
        }

        return $str->get();
    }

    /**
     * Return html output string.
     * 
     * @return  string
     */

    public function output()
    {
        return $this->output;
    }

}