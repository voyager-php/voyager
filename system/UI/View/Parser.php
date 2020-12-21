<?php

namespace Voyager\UI\View;

use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Reader;
use Voyager\Util\Str as Builder;

class Parser
{
    /**
     * Functions that can be replaced.
     * 
     * @var array
     */

    private static $directives = [
        'if',
        'elseif',
        'else',
        'for',
        'foreach',
    ];

    /**
     * Functions closing directive.
     * 
     * @var array
     */

    private static $endDirectives = [
        'endif',
        'endfor',
        'endforeach',
    ];

    /**
     * Callable index.
     * 
     * @var int
     */

    private $index = 0;

    /**
     * Store callable index.
     * 
     * @var \Voyager\Util\Arr
     */

    private $callable;

    /**
     * Return html output.
     * 
     * @var string 
     */

    private $html;

    /**
     * Create new parser instance.
     * 
     * @param   string $html
     * @return  void
     */

    public function __construct(string $html)
    {
        $this->callable = new Arr();
        
        $html = $this->replaceIncludes($html);
        $html = $this->replaceFunctions($html);
        $html = $this->replaceDirectives($html);
        $html = $this->replaceEndScopes($html);
        $html = $this->replaceTemplates($html);
        $html = $this->replaceUnescapedTemplates($html);
        $html = $this->replaceDynamicAttribute($html);
        $html = $this->replaceAllCallable($html);
        $html = $this->removeTemplateComments($html);
        $html = $this->removeNativeComments($html);

        $this->html = $html;
    }

    /**
     * Return html string output.
     * 
     * @return  string
     */

    public function get()
    {
        return $this->html;
    }

    /**
     * Replace all callable keywords with PHP code.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceAllCallable(string $html)
    {
        if(!Str::has($html, '[####'))
        {
            return $html;
        }

        $str = new Builder();
        foreach(explode('[#### ', $html) as $segment)
        {
            if(Str::has($segment, ' ####]'))
            {
                $break = Str::break($segment, ' ####]');
                $str->append($this->callable->get($break[0]) . ($break[1] ?? ''));
            }
            else
            {
                $str->append('[####' . $segment);
            }
        }

        if($str->startWith('[####'))
        {
            $str->move(5);
        }
        
        return $str->get();
    }

    /**
     * Return true if html string contains tag.
     * 
     * @param   string $tag
     * @param   string $html
     * @return  bool
     */

    private static function hasTag(string $tag, string $html)
    {
        return Str::has($html, '<' . $tag) && Str::has($html, '</' . $tag . '>');
    }

    /**
     * Return the content html of the input tag name.
     * 
     * @param   string $tag
     * @param   string $html
     * @return  string
     */

    public static function innerHTML(string $tag, string $html)
    {
        if(static::hasTag($tag, $html))
        {
            return Str::break(Str::break(Str::break($html, '<' . $tag)[1], '>')[1], '</' . $tag . '>')[0];
        }
    }

    /**
     * Return inner html of template tag.
     * 
     * @param   string $html
     * @return  string
     */

    public static function template(string $html)
    {
        return static::innerHTML('template', $html);
    }

    /**
     * Return script source from html.
     * 
     * @param   string $html
     * @return  string
     */

    public static function script(string $html)
    {
        return static::innerHTML('script', $html);
    }

    /**
     * Return stylesheet source from html.
     * 
     * @param   string $html
     * @return  string
     */

    public static function style(string $html)
    {
        return static::innerHTML('style', $html);
    }

    /**
     * Return template attribute value.
     * 
     * @param   string $tag
     * @param   string $html
     * @return  array
     */

    public static function getAttribute(string $tag, string $html)
    {
        if(static::hasTag($tag, $html))
        {
            $data = [];
            $parse = Str::break(Str::break($html, '<' . $tag)[1], '>')[0];
            
            if(Str::has($parse, ' '))
            {
                $tag = Str::break($parse, ' ')[1] . " ";
                $props = array_diff(explode('" ', $tag), [""]);
            
                foreach($props as $prop)
                {
                    $break = Str::break($prop, '="');
                    $data[$break[0]] = $break[1];
                }
            }

            return $data;
        }
    }

    /**
     * Replace templates with PHP code.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceTemplates(string $html)
    {
        if(!Str::has($html, '{{'))
        {
            return $html;
        }

        $str = new Builder();

        foreach(explode('{{', $html) as $segment)
        {
            if(Str::has($segment, '}}'))
            {
                if(!Str::startWith($segment, '--') && !Str::endWith($segment, '--'))
                {
                    $contain = Str::break($segment, '}}');
                    
                    if(str_replace(' ', '', $contain[0]) === '$slot')
                    {
                        $this->callable->push('<?php echo ' . $contain[0]. '; ?>');
                    }
                    else
                    {
                        $this->callable->push('<?php echo htmlspecialchars(' . $contain[0]. '); ?>');
                    }

                    $str->append('[#### ' . $this->index . ' ####]' . $contain[1]);
                    $this->index++;
                }
                else
                {
                    $str->append('{{' . $segment);
                }
            }
            else
            {
                $str->append('{{' . $segment);
            }
        }

        if(!$str->empty() && $str->startWith('{{'))
        {
            $str->move(2);
        }

        return $str->get();
    }

    /**
     * Replace templates without escaping the result.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceUnescapedTemplates(string $html)
    {
        if(!Str::has($html, '{%'))
        {
            return $html;
        }

        $str = new Builder();

        foreach(explode('{%', $html) as $segment)
        {
            if(Str::has($segment, '%}'))
            {
                $contain = Str::break($segment, '%}');

                $this->callable->push('<?php echo ' . $contain[0]. '; ?>');
                $str->append('[#### ' . $this->index . ' ####]' . $contain[1]);
                $this->index++;
            }
            else
            {
                $str->append('{%' . $segment);
            }
        }

        if(!$str->empty() && $str->startWith('{%'))
        {
            $str->move(2);
        }

        return $str->get();
    }

    /**
     * Replace include directives.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceIncludes(string $html)
    {   
        if(!Str::has($html, '@include'))
        {
            return $html;
        }

        $str = new Builder();
        
        foreach(explode('@include', $html) as $segment)
        {
            if(Str::startWith($segment, '('))
            {
                $func = Str::move(Str::break($segment, ')')[0], 2, 1);
                $file = new Reader(TemplateEngine::resourcePath(str_replace('.', '/', $func)));
                $trail = Str::break($segment, ')')[1] ?? '';
                
                if($file->exist())
                {
                    $content = $file->content();
                    $script = Parser::script($content);
                    $styles = Parser::style($content);
                    $hash = Str::hash($func);

                    if(!is_null($script))
                    {
                        TemplateEngine::addScript($hash, $script);
                    }

                    if(!is_null($styles))
                    {
                        TemplateEngine::addStylesheet($hash, $styles);
                    }

                    $str->append(Parser::template($content) . $trail);
                }
                else
                {
                    $str->append($trail);
                }
            }
            else
            {
                $str->append($segment);
            }
        }
        
        return $str->get();
    }

    /**
     * Replace directives functions.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceFunctions(string $html)
    {
        if(!Str::has($html, '@'))
        {
            return $html;
        }

        Directives::get();
        $str = new Builder();

        foreach(explode('@', $html) as $segment)
        {
            if(Str::startWith($segment, Directives::keys()))
            {
                $directive = trim(Str::break($segment, "\n")[0]);
                $name = Str::break($directive, '(')[0];
                $value = null;

                if(Str::has($directive, '('))
                {
                    $value = Str::move(Str::break($directive, '(')[1], 0, 1);
                }

                $this->callable->push('<?php echo Voyager\UI\View\Directives::call(\'' . $name . '\',\'' . $value . '\'); ?>');
                $str->append('[#### ' . $this->index . ' ####]' . "\n" . Str::move($segment, strlen($directive)));
                $this->index++;
            }
            else
            {
                $str->append('@' . $segment);
            }
        }

        if(!$str->empty() && $str->startWith('@'))
        {
            $str->move(1);
        }

        return Str::moveFromStart($str, '@');
    }

    /**
     * Replace directives with scopes.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceDirectives(string $html)
    {
        if(!Str::has($html, '@'))
        {
            return $html;
        }

        $directives = static::$directives;
        $str = new Builder();

        foreach(explode('@', $html) as $segment)
        {
            if(Str::startWith($segment, $directives))
            {
                $break = Str::break($segment, "\n");
                
                if(Str::equal(Str::break($break[0], '(')[0], ['else', 'elseif']))
                {
                    $this->callable->push('<?php } ' . $break[0] . ' { ?>');
                    $str->append('[#### ' . $this->index . ' ####]' . "\n" . ($break[1] ?? ''));
                }
                else
                {
                    $this->callable->push('<?php ' . $break[0] . ' { ?>');
                    $str->append('[#### ' . $this->index . ' ####]' . "\n" . ($break[1] ?? ''));
                }

                $this->index++;
            }
            else
            {
                $str->append('@' . $segment);
            }
        }

        return Str::moveFromStart($str, '@');
    }

    /**
     * Replace scopes closing by PHP code.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceEndScopes(string $html)
    {
        if(!Str::has($html, '@'))
        {
            return $html;
        }

        $str = new Builder();
        
        foreach(explode('@', $html) as $segment)
        {
            if(Str::startWith($segment, static::$endDirectives))
            {
                $this->callable->push('<?php } ?>');
                $str->append('[#### ' . $this->index . ' ####]');
                $this->index++;
                $trail = $segment;

                foreach(static::$endDirectives as $type)
                {
                    if(Str::startWith($segment, $type))
                    {
                        $trail = Str::move($segment, strlen($type));
                    }
                }

                $str->append($trail);
            }
            else
            {
                $str->append('@' . $segment);
            }
        }

        return Str::moveFromStart($str, '@');
    }

    /**
     * Replace dynamic attributes with PHP code.
     * 
     * @param   string $html
     * @return  string
     */

    private function replaceDynamicAttribute(string $html)
    {
        $str = new Builder();

        foreach(explode('<', $html) as $tag)
        {
            if(!Str::startWith($tag, ['/', '?', '!']) && $tag !== '')
            {
                if(Str::has($tag, '>'))
                {
                    $pos = strpos($tag, ' ');
                    $props = $pos ? Str::move(Str::break($tag, '>')[0], $pos) : '';
                    $props = $props === false ? '' : $props;

                    if(!empty($props))
                    {
                        $str->append('<' . Str::break($tag, ' ')[0] . ' ');
                        $props = Str::moveFromBothEnds($props, ' ');
                        $content = Str::break($tag, '>')[1] ?? null;
                        $attrs = explode('" ', $props);
                        $first = $attrs[0] ?? null;
                        
                        foreach($attrs as $attr)
                        {
                            if(!empty($attr) && Str::startWith($attr, ':'))
                            {
                                $name = Str::move(Str::break($attr, '=')[0], 1);
                                $value = Str::moveFromEnd(Str::break($attr, '="')[1], '"');
                                
                                if($name === 'class')
                                {
                                    $str->moveFromEnd(' ');
                                    $classes = new Arr();

                                    foreach(explode(' ', $value) as $class)
                                    {
                                        if(Str::startWith($class, '$'))
                                        {
                                            $this->callable->push('<?php echo htmlspecialchars(' . $class . '); ?>');
                                            $classes->push('[#### ' . $this->index . ' ####]');
                                            $this->index++;
                                        }
                                        else
                                        {
                                            $classes->push($class);
                                        }
                                    }

                                    if(Str::startWith($first, ':class'))
                                    {
                                        $str->append(' class="' . $classes->implode(' ') . '" ');
                                    }
                                    else
                                    {
                                        $str->append('class="' . $classes->implode(' ') . '" ');
                                    }
                                }
                                else
                                {
                                    $this->callable->push('<?php
                                    
                                    if(!empty(' . $value . ') && !is_null(' . $value . ') && ' . $value . ' !== false) {
                                        if(' . $value . ' === true) {
                                            echo \'' . $name . ' \';
                                        }
                                        else 
                                        {
                                            echo \'' . $name . '="\' . htmlspecialchars(' . $value . ') . \'" \';
                                        }
                                    }

                                    ?>');

                                    $str->append('[#### ' . $this->index . ' ####]');
                                    $this->index++;
                                }
                            }
                            else
                            {
                                if($attr !== '/' && !Str::endWith($attr, '=""'))
                                {
                                    if(Str::has($attr, '="'))
                                    {
                                        $str->append(Str::moveFromBothEnds($attr, '"') . '" ');
                                    }
                                    else
                                    {
                                        $str->append(Str::moveFromEnd($attr, '"'));
                                    }
                                }
                                else if(Str::endWith($attr, '=""'))
                                {
                                    $str->append($attr);
                                }
                            }
                        }

                        $str->moveFromEnd(' ')->append('>');

                        if(!is_null($content))
                        {
                            $str->append($content);
                        }
                    }
                    else
                    {
                        $str->append('<' . $tag);
                    }
                }
                else
                {
                    $str->append($tag);
                }
            }
            else
            {
                $str->append('<' . $tag);
            }
        }

        if(Str::startWith($str, '<<'))
        {
            $str = Str::move($str, 1);
        }
        else
        {
            $str = $str->get();
        }

        return $str;
    }

    /**
     * Return html string rendered without template comments.
     * 
     * @param   string $html
     * @return  string
     */

    public function removeTemplateComments(string $html)
    {
        if(!Str::has($html, '{{--') || !Str::has($html, '--}}'))
        {
            return $html;
        }

        $str = new Builder();
        
        foreach(explode('{{--', $html) as $segment)
        {
            if(Str::has($segment, '--}}'))
            {
                $str->append(Str::break($segment, '--}}')[1]);
            }
            else
            {
                $str->append('{{--' . $segment);
            }
        }

        if(!$str->empty() && Str::startWith($str, '{{--'))
        {
            $str->move(4);
        }

        if(!$str->empty())
        {
            return $str->get();
        }

        return $html;
    }

    /**
     * Return html string rendered without native comments.
     * 
     * @param   string $html
     * @return  string
     */

    private function removeNativeComments(string $html)
    {
        if(!Str::has($html, '<!--') || !Str::has($html, '-->'))
        {
            return $html;
        }

        $str = new Builder();

        if(Str::has($html, '<!--') && Str::has($html, '-->'))
        {
            foreach(explode('<!--', $html) as $comment)
            {
                if(Str::has($comment, '-->'))
                {
                    $str->append(Str::break($comment, '-->')[1]);
                }
                else
                {
                    $str->append('<!--' . $comment);
                }
            }
        }

        if(!$str->empty() && Str::startWith($str, '<!--'))
        {
            $str->move(4);
        }

        if(!$str->empty())
        {
            return $str->get();
        }

        return $html;
    }
    
}