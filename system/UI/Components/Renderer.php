<?php

namespace Voyager\UI\Components;

use Voyager\Facade\Str;
use Voyager\UI\View\TemplateEngine;
use Voyager\Util\Arr;

class Renderer
{
    /**
     * Store registered components.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $registered;

    /**
     * Start component rendering.
     * 
     * @param   string $html
     * @return  string
     */

    public static function start(string $html)
    {
        static::$registered = new Arr();

        $length = strlen($html);
        $pos = strpos($html, '<v-');
        $rendered = false;

        if(!$pos)
        {
            $pos = $length;
        }

        while($pos < $length)
        {
            $begin = substr($html, 0, $pos);
            $str = substr($html, $pos + 3, $length);
            $tag = Str::break($str, '>')[0];
            $name = Str::break($tag, ' ')[0];
            $end = strpos($str, '</v-' . $name . '>');
            $props = Str::move($tag, strlen($name));
            $span = substr($html, $pos, $length);
            $content = null;
            $data = [];

            if(Str::endWith($tag, '; ?'))
            {
                $props .= '>"';
            }

            if($end)
            {
                $content = Str::break(Str::break($str, '>')[1], '</v-' . $name . '>')[0];
                $span = Str::break($span, '</v-' . $name . '>')[0] . '</v-' . $name . '>';
                
                if(Str::startWith($content, '">'))
                {
                    $content = Str::move($content, 2);
                }
            }
            else
            {
                $props = Str::move($props, 0, 1);
                
                if($props === ' ')
                {
                    $props = Str::move($props, 0, 1);
                }
                else
                {
                    if(!Str::endWith($props, ' '))
                    {
                        $props .= ' ';
                    }
                }
                
                $span = Str::break($span, '>')[0] . '>';
            }

            $ending = substr($html, $pos + strlen($span), $length);
            
            $refs = new Arr(Config::get());
            $output = $span;

            if($refs->hasKey($name))
            {
                if(!static::$registered->has($name))
                {
                    static::$registered->push($name);
                }

                $ref = $refs->get($name);
                $instance = new $ref();
                
                if(!empty($props))
                {
                    $props = Str::moveFromBothEnds($props, ' ');

                    foreach(explode('" ', $props) as $attr)
                    {
                        $attr = Str::break(Str::moveFromEnd($attr, '"'), '="');
                        $data[Str::moveFromEnd($attr[0], ' ')] = $attr[1] ?? null;
                    }
                }

                if(!is_null($content))
                {
                    $content = Str::moveFromBothEnds($content, ' ');
                    $content = Str::moveFromBothEnds($content, PHP_EOL);
                }

                $instance->slot = $content;

                foreach($data as $key => $value)
                {
                    if(is_null($value) || $value === 'true')
                    {
                        $instance->{$key} = true;
                    }
                    else if($value === 'false')
                    {
                        $instance->{$key} = false;
                    }
                    else if(is_int($value))
                    {
                        $instance->{$key} = (int)$value;
                    }
                    else
                    {
                        $instance->{$key} = $value;
                    }
                }

                $generated = $instance->generate();
                $show = $instance->show;

                if(is_string($show))
                {
                    $show = ($show === 'true') ? true : false;
                }

                $output = $show ? $generated['html'] : '';
                $rendered = true;
            
                if(!is_null($generated['script']) && $show)
                {
                    TemplateEngine::addScript($name, $generated['script']);
                }

                if(!is_null($generated['style']) && $show)
                {
                    TemplateEngine::addStylesheet($name, $generated['style']);
                }
            }

            $html = $begin . $output . $ending;
            $length = strlen($html);
            $pos = strpos($html, '<v-');

            if(!$pos)
            {
                $pos = $length;
            }

            if(!$rendered)
            {
                break;
            }
        }

        return $html;
    }

}