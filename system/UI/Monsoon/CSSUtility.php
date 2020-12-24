<?php

namespace Voyager\UI\Monsoon;

use Voyager\Facade\Str;
use Voyager\UI\View\TemplateEngine;
use Voyager\Util\Arr;
use Voyager\Util\File\Reader;
use Voyager\Util\Str as Builder;

class CSSUtility
{
    /**
     * Store config data.
     * 
     * @var array
     */

    private static $config;

    /**
     * Store css utility classes.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $classes;

    /**
     * Location of css utility.
     * 
     * @var string
     */

    private static $utility_path = 'system/UI/Monsoon/utilities.php';

    /**
     * Supported pseudo classes.
     * 
     * @var array
     */

    private static $pseudos = [
        'hover',
        'active',
        'focus',
        'visited',
    ];

    /**
     * Extract utility classes exist from html.
     * 
     * @param   string $html
     * @return  string
     */

    public static function extract(string $html)
    {
        static::$classes = new Arr();
        static::load();
        static::$config = cache('monsoon') ?? Config::get();

        $classes = new Arr();
        $mediaqueries = new Arr();
        $screens = new Arr(static::$config['screens']);
        $pseudos = new Arr(static::$pseudos);
        $str = new Builder();

        foreach(explode('<', $html) as $tag)
        {
            if(!Str::startWith($tag, ['!', '/']) && $tag !== '' && Str::has($tag, 'class="'))
            {
                $break = Str::break($tag, 'class="');
                $split = explode(' ', Str::break($break[1], '"')[0]);
                $css = new Arr();

                foreach($split as $util)
                {
                    $negative = Str::startWith($util, '-');
                    $util = Str::moveFromStart(Str::moveFromEnd($util, '!'), '-');
                    $pseudo = Str::break($util, ':')[0];
                    $mediaquery = false;

                    if(Str::has($util, ':'))
                    {
                        $util = Str::break($util, ':')[1];

                        if($pseudos->has($pseudo) || $screens->hasKey($pseudo))
                        {
                            if($screens->hasKey($pseudo))
                            {
                                $mediaquery = true;
                            }
                        }
                        else
                        {
                            $pseudo = null;
                        }
                    }
                    else
                    {
                        $pseudo = null;
                    }

                    if(static::$classes->hasKey($util))
                    {
                        if($mediaquery)
                        {
                            $key = $pseudo . '-' . $util;
                            $css->push($key);

                            if($mediaqueries->hasKey($pseudo))
                            {
                                $data = new Arr($mediaqueries->get($pseudo));
                                $data->push(static::generate($key, $util, null, $negative));
                                $mediaqueries->set($pseudo, $data->unique()->get());
                            }
                            else
                            {
                                $mediaqueries->set($pseudo, [static::generate($key, $util, null, $negative)]);
                            }
                        }
                        else
                        {
                            $css->push($util);
                            $cache = cache('util_' . $util);

                            if(is_null($cache))
                            {
                                $cache = cache('util_' . $util, static::generate($util, $util, $pseudo, $negative));
                            }

                            $classes->set($util, $cache);
                        }
                    }
                    else
                    {
                        $array = new Arr(explode('-', $util));
                        $value = $array->last();
                        $class = $array->pop()->implode('-');

                        if(static::$classes->hasKey($class))
                        {
                            if($mediaquery)
                            {
                                $key = $pseudo . '-' . $util;
                                $css->push($key);

                                if($mediaqueries->hasKey($pseudo))
                                {
                                    $data = new Arr($mediaqueries->get($pseudo));
                                    $data->push(static::generate($key, $class, null, $negative, $value));
                                    $mediaqueries->set($pseudo, $data->unique()->get());
                                }
                                else
                                {
                                    $mediaqueries->set($key, [static::generate($key, $class, null, $negative, $value)]);
                                }
                            }
                            else
                            {
                                $css->push($util);
                                $cache = cache('util_' . $util);
                                
                                if(is_null($cache))
                                {
                                    $cache = cache('util_' . $util, static::generate($util, $class, $pseudo, $negative, $value));
                                }

                                $classes->set($util, $cache);
                            }
                        }
                        else
                        {
                            $css->push($util);
                        }
                    }
                }

                $str->append('<' . $break[0]);
                $str->append('class="' . Str::moveFromBothEnds($css->implode(' '), ' ') . '"');
                $str->append(Str::break($break[1], '"')[1]);
            }
            else
            {
                $str->append('<' . $tag);
            }
        }

        foreach($mediaqueries->get() as $key => $item)
        {
            $media = new Builder('@media only screen and (max-width:' . $screens->get($key) . 'px){');

                foreach($item as $css)
                {
                    $media->append($css);
                }

            $media->append('}');

            TemplateEngine::addStylesheet($key . '-utilities', $media->get());
        }

        if(!$classes->empty())
        {
            TemplateEngine::addStylesheet('monsoon-utilities', $classes->implode(PHP_EOL));
        }

        if($str->startWith('<<'))
        {
            $str->move(1);
        }
        
        return $str->get();
    }

    /**
     * Generate css codes.
     * 
     * @param   string $util
     * @param   string $pseudo
     * @param   bool $negative
     * @param   mixed $value
     * @return  string
     */

    private static function generate(string $util, string $classname, string $pseudo = null, bool $negative = false, $val = null)
    {
        if(static::$classes->hasKey($classname))
        {
            $data = static::$classes->get($classname);
            $props = $data['props'];
            $default = new Arr($data['default']);
            $units = new Arr($data['units']);
            $instead = new Arr($data['instead']);
            $colors = new Arr($data['colors']);
            $important = new Arr($data['important']);
            $configuration = static::$config['default'];
            $str = new Builder('.' . $util);
            
            if(!is_null($pseudo))
            {
                $str->append(':' . $pseudo);
            }

            $str->append('{');

            foreach($props as $key => $value)
            {
                $str->append($key . ':' . $value);
        
                if($important->has($key))
                {
                    $str->append(' !important');
                }
                    
                $str->append(';');
            }

            foreach($default->get() as $key => $value)
            {
                $config = $configuration[$value];
            
                if(is_null($val) && $instead->hasKey($key))
                {
                    $str->append($key . ':' . $instead->get($key));
                }
                else if(array_key_exists($val, $config))
                {
                    $str->append($key . ':' . $config[$val]);
                }
                else
                {
                    if(is_null($val) && $instead->hasKey($key))
                    {
                        $str->append($key . ':' . $instead->get($key));
                    }   
                    else
                    {
                        $str->append($key . ':' . $val . ($units->hasKey($key) ? $units->get($key) : ''));
                    }
                }

                if($important->has($key))
                {
                    $str->append(' !important');
                }

                $str->append(';');
            }

            foreach($colors->get() as $key => $value)
            {
                $config = $configuration['colors'];
            
                if(array_key_exists($value, $config))
                {
                    $color = $config[$value];
                
                    if(is_string($color))
                    {
                        $str->append($key . ':' . $color);
                    }
                    else if(is_array($color))
                    {
                        if(array_key_exists($val, $color))
                        {
                            $str->append($key . ':' . $color[$val]);
                        }
                    }
                }
                else
                {
                    $str->append($key . ':' . $val);
                }

                if($important->has($key))
                {
                    $str->append(' !important');
                }

                $str->append(';');
            }

            $str->append('}');

            return $str->get();
        }
    }

    /**
     * Load css utilities from utilities folder.
     * 
     * @return  void
     */

    private static function load()
    {
        $cache = cache('css-utilities');

        if(is_null($cache))
        {
            $file = new Reader(static::$utility_path);
            
            if($file->exist())
            {
                $file->require();

                foreach(Util::get()->get() as $item)
                {
                    static::$classes->set($item->getName(), $item->data()->toArray());
                }
            }

            cache('css-utilities', static::$classes->get());
        }
        else
        {
            static::$classes = new Arr($cache);
        }
    }

}