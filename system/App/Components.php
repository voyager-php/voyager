<?php

namespace Voyager\App;

use Voyager\UI\View\Parser;
use Voyager\UI\View\TemplateEngine;
use Voyager\Util\Arr;
use Voyager\Util\File\Reader;
use Voyager\Util\Str as Builder;

abstract class Components
{
    /**
     * Store component attribute data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $attributes;

    /**
     * HTML attributes that can be defined from parent class.
     * 
     * @var array
     */

    protected $prop = [];

    /**
     * Store data that can be used by the component.
     * 
     * @var array
     */

    protected $data = [];

    /**
     * Create new component instance.
     * 
     * @return  void
     */

    public function __construct()
    {
        $this->created();
        $this->attributes = new Arr([
            'id'            => null,
            'slot'          => null,
            'show'          => true,
        ]);

        $this->attributes->merge($this->prop);
    }

    /**
     * Check if data key exist.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function has(string $key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Set component data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function set(string $key, $value)
    {
        if($this->has($key))
        {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Return component data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function get(string $key)
    {
        if($this->has($key))
        {
            return $this->data[$key];
        }
    }

    /**
     * Return dynamic attribute values.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function __get(string $key)
    {
        if($this->attributes->hasKey($key))
        {
            return $this->attributes->get($key);
        }
    }

    /**
     * Set attribute value and call corresponding method.
     * 
     * @param   string $key
     * @param   string $value
     * @return  void
     */

    public function __set(string $key, $value)
    {
        if($this->attributes->hasKey($key) && $this->attributes->get($key) !== $value)
        {
            $this->updated($value);
            $this->attributes->set($key, $value);
            
            if(method_exists($this, $key))
            {
                $this->{$key}($value);
            }
        }
    }

    /**
     * Call when component is rendered.
     * 
     * @return  void
     */

    protected function rendered() {}

    /**
     * Call each time attribute value is updated.
     * 
     * @param   mixed $value
     * @return  void
     */

    protected function updated($value) {}

    /**
     * Call each time component is created.
     * 
     * @return  void
     */

    protected function created() {}

    /**
     * Render components.
     * 
     * @return  array
     */

    public function generate()
    {
        $this->rendered();

        $component = get_called_class();
        $cache_html = cache('comp_html_' . $component);
        $cache_css = cache('comp_css_' . $component);
        $cache_js = cache('comp_js_' . $component);
        
        if(is_null($cache_html))
        {
            $namespace = new Builder($component);
            $reader = new Reader($namespace->move(10)->replace('\\', '/')->append('.html')->prepend('resource/view/component')->get());

            if($reader->exist())
            {
                $content = trim($reader->content());
                $cache_html = cache('comp_html_' . $component, Parser::template($content));
                $cache_css = cache('comp_css_' . $component, Parser::style($content));
                $cache_js = cache('comp_js_' . $component, Parser::script($content));
            }
        }

        $parser = new Parser($cache_html);

        return [
            'html'      => TemplateEngine::compile($parser->get(), $this->attributes->merge($this->data)),
            'script'    => $cache_js,
            'style'     => $cache_css,
        ];
    }

}