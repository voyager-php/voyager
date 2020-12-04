<?php

namespace Voyager\App;

use Voyager\UI\View\Parser;
use Voyager\UI\View\TemplateEngine;
use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;
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

    protected $attr = [];

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

        $this->attributes->merge($this->attr);
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
     * Set attribute value.
     * 
     * @param   string $key
     * @param   string $value
     * @return  void
     */

    public function __set(string $key, $value)
    {
        $this->attributes->set($key, $value);
        $this->updated($value);
            
        if(method_exists($this, $key))
        {
            $this->{$key}($value);
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
     * @return  \Voyager\Util\Data\Collection
     */

    public function generate()
    {
        $this->rendered();
        $namespace = new Builder(get_called_class());
        $reader = new Reader($namespace->move(10)->replace('\\', '/')->append('.html')->prepend('resource/view/component')->get());
        
        if($reader->exist())
        {
            $content = $reader->content();
            $html = new Parser(Parser::template($content));
            
            return new Collection([
                'html'      => TemplateEngine::compile($html->get(), $this->attributes),
                'script'    => Parser::script($content),
                'style'     => Parser::style($content),
            ]);
        }
    }

}