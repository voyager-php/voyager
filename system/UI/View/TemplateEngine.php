<?php

namespace Voyager\UI\View;

use Voyager\Facade\Str;
use Voyager\Resource\Locale\Lang;
use Voyager\UI\Components\Renderer;
use Voyager\UI\Monsoon\Config;
use Voyager\UI\Monsoon\CSSUtility;
use Voyager\Util\Arr;
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
        $this->uri = app()->request()->uri();
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
            static::$hash = Str::hash($this->uri);
        }

        $cache = new Reader(static::cachePath(Str::hash($this->uri)));
        
        if($cache->exist())
        {
            $this->output = $cache->content();
        }
        else
        {
            $html = new Parser($this->extendLayout($this->loadContent()));
            $html = static::compile($html->get(), $this->emit);

            if(Str::has($html, '<v-') && Str::has($html, '</v-'))
            {
                $html = Renderer::start($html);
            }

            $config = cache('monsoon') ?? Config::get();

            if($config['enable'] && Str::has($html, 'class="'))
            {
                $html = CSSUtility::extract($html);
            }

            $this->output = $this->makeExternalResource($html);
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
        if(Str::has($html, '</head>'))
        {
            $break = Str::break($html, '</head>');
            $cache = cache('app_css');

            if(is_null($cache))
            {
                $app_css = new Reader('resource/css/app.css');

                if($app_css->exist())
                {
                    $cache = cache('app_css', trim($app_css->content()));
                }
            }

            static::addStylesheet('app_css', $cache);

            $source = static::$styles->reverse()->join();

            if(env('APP_COMPRESS'))
            {
                $source = preg_replace("/\r|\n/", "", $source);
            }

            $builder = new Builder('const app={');
            $builder->br();

            $builder->append('authenticated: ' . (auth()->authenticated() ? 'true' : 'false') . ',')
                    ->br();
            $builder->append('authID: "' . auth()->id() . '",')
                    ->br();
            $builder->append('authType: ' . auth()->type() . ',')
                    ->br();
            $builder->append('authUserId: "' . auth()->userId() . '",')
                    ->br();

            $builder->append('get: function(){return JSON.parse("')
                    ->append(addslashes(app()->request()->get()->toJson()))
                    ->append('");},')
                    ->br();

            $builder->append('post: function(){return JSON.parse("')
                    ->append(addslashes(app()->request()->post()->toJson()))
                    ->append('");},')
                    ->br();

             $builder->append('resource: function(){return JSON.parse("')
                    ->append(addslashes(json_encode(app()->route('resource'))))
                    ->append('");},')
                    ->br();

            $builder->append('token:"')
                    ->append(csrf_token())
                    ->append('"')
                    ->br();

            $builder->append('};');

            $html = $break[0] . '<style type="text/css">' . PHP_EOL . $source . '</style><script type="text/javascript">' . $builder->get() . '</script></head>' . $break[1];
        }
        
        if(Str::has($html, '</body>'))
        {
            $break = Str::break($html, '</body>');
            $cache = cache('app_js');

            if(is_null($cache))
            {
                $app_js = new Reader('resource/js/app.js');

                if($app_js->exist())
                {
                    $cache = cache('app_js', trim($app_js->content()));
                }
            }

            $source = static::$script->reverse()->join();

            if(env('APP_COMPRESS'))
            {
                $source = preg_replace("/\r|\n/", "", $source);
            }

            $output = new Builder($break[0]);
            $core = new Reader('public/js/core.js');

            if(!$core->exist() || !app()->cache)
            {
                $dir = new Directory('public/js/');

                if(!$dir->exist())
                {
                    $dir = new Directory('public/');
                    $dir->makeFolder('js');
                }

                $scripts   = [$cache];
                $scripts[] = 'voyager.setProperty(\'version\',\'' . app()->version() . '\');'; 
                $scripts[] = 'voyager.setProperty(\'locale\',\'' . env('APP_LOCALE') . '\');';
                $scripts[] = 'voyager.setProperty(\'backup_locale\', \'' . env('APP_BACKUP_LOCALE') . '\');';
                $scripts[] = 'voyager.setProperty(\'app_url\',\'' . env('APP_URL') . '\');';
                $scripts[] = 'voyager.setProperty(\'app_name\',\'' . env('INFO_NAME') . '\');';
                $scripts[] = 'voyager.setProperty(\'app_description\',\'' . env('INFO_DESCRIPTION') . '\');';
                $scripts[] = 'voyager.setProperty(\'app_version\',\'' . env('INFO_VERSION') . '\');';

                $request = app()->request();
                
                $scripts[] = 'voyager.setProperty(\'current_url\',\'' . addslashes($request->url()) . '\');';
                $scripts[] = 'voyager.setProperty(\'base_url\', \'' . addslashes($request->baseURL()) . '\');';
                $scripts[] = 'voyager.setProperty(\'uri\',\'' . addslashes($request->uri()) . '\');';

                $data = [];
                $locale = Lang::load();

                foreach($locale as $group_id => $group)
                {
                    $items = [];

                    foreach($group as $key => $item)
                    {
                        $items[str_replace('.', '_', $key)] = $item;
                    }
        
                    $data[$group_id] = $items;
                }

                $scripts[] = 'voyager.setProperty(\'translations\', JSON.parse("' . addslashes(json_encode($data)) . '"));';

                $core->delete();
                $core->make(preg_replace("/\r|\n/", "", implode('', $scripts)));
            }
            
            $output->append('<script type="text/javascript" src="' . url('/js/core.js') . '"></script>');
            
            if(!empty($source))
            {
                $output->append('<script type="text/javascript">')
                       ->append($source)
                       ->append('</script>');
            }

            $html = $output->append('</body>' . $break[1])->get();
        }

        return $html;
    }

    /**
     * Wrap content with extended layout.
     * 
     * @param   array
     * @return  string
     */

    private function extendLayout(array $data)
    {
        $layout = $data['layout'];

        if(!is_null($layout))
        {
            $html = cache('layout_' . $layout);
            $css = $data['styles'];
            $js = $data['script'];

            if(is_null($html))
            {
                $reader = new Reader(static::resourcePath('layout/' . str_replace('.', '/', $layout)));

                if($reader->exist())
                {
                    $html = cache('layout_' . $layout, trim($reader->content()));
                }
            }

            if(!is_null($css))
            {
                static::addStylesheet('layout_css', $css);
            }

            if(!is_null($js))
            {
                static::addScript('layout_js', $js);
            }

            return str_replace('@content', $data['content'], $html);
        }

        return $data['content'];
    }

    /**
     * Load html content.
     * 
     * @return array
     */

    private function loadContent()
    {
        $cache = cache($this->content);

        if(is_null($cache))
        {
            $reader = new Reader(static::resourcePath($this->content));

            if($reader->exist())
            {
                $cache = cache($this->content, trim($reader->content()));
            }
        }

        $attribute = Parser::getAttribute('template', $cache);

        if(!$this->emit->hasKey('title'))
        {
            $this->emit->set('title', $attribute['title'] ?? 'Untitled Page');
        }

        foreach($attribute as $key => $value)
        {
            if($key !== 'extend' && $key !== 'title')
            {
                $this->emit->set($key, $value);
            }
        }

        return [
            'content'           => Parser::template($cache),
            'layout'            => $attribute['extend'] ?? null,
            'script'            => Parser::script($cache),
            'styles'            => Parser::style($cache),
        ];
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
        if(Str::has($string, '/**') && Str::has($string, '*/'))
        {
            return $string;
        }

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