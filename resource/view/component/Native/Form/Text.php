<?php

namespace Components\Native\Form;

use Voyager\App\Components;
use Voyager\Facade\Str;
use Voyager\UI\Monsoon\Config;

class Text extends Components
{
    /**
     * Textbox component properties.
     * 
     * @var array
     */

    protected $prop = [
        'type'                  => 'text',
        'name'                  => null,
        'placeholder'           => null,
        'value'                 => null,
        'spellcheck'            => true,
        'size'                  => 'medium',
        'bordercolor'           => 'gray-500',
        'hovercolor'            => 'gray-600',
        'highlight'             => 'blue-500',
        'color'                 => 'black',
        'autofocus'             => false,
        'rounded'               => true,
        'disabled'              => false,
        'onkeydown'             => null,
        'onkeyup'               => null,
        'onkeypress'            => null,
        'onfocus'               => null,
        'onblur'                => null,
    ];

    /**
     * Set default component properties.
     * 
     * @return  void
     */

    protected function created()
    {
        $this->highlight = 'blue-500';
    }

    /**
     * Component's internal variables.
     * 
     * @var array
     */

    protected $data = [
        'text_size'             => 'textbox-md',
        'border_color'          => 'border-gray-500',
        'hover_color'           => 'hover:border-gray-600',
        'border_radius'         => 'rounded',
        'text_color'            => 'text-black',
    ];

    /**
     * Set textbox component size.
     * 
     * @param   string $size
     * @return  void
     */

    protected function size(string $size)
    {
        $size = strtolower($size);
    
        if($size === 'small')
        {
            $this->set('text_size', 'textbox-sm');
        }
        else if($size === 'medium')
        {
            $this->set('text_size', 'textbox-md');
        }
        else if($size === 'large')
        {
            $this->set('text_size', 'textbox-lg');
        }
    }

    /**
     * Set textbox font color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function color(string $color)
    {
        $this->set('text_color', 'text-' . $color);
    }

    /**
     * Set textbox border color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function bordercolor(string $color)
    {
        $this->set('border_color', 'border-' . $color);
    }

    /**
     * Set textbox border radius.
     * 
     * @param   bool $rounded
     * @return  void
     */

    protected function rounded(bool $rounded)
    {
        if($rounded)
        {
            $this->set('border_radius', 'rounded');
        }
        else
        {
            $this->set('border_radius', 'rounded-none');
        }
    }

    /**
     * Set border color when textbox is focused.
     * 
     * @param   string $color
     * @return  void
     */

    protected function highlight(string $color)
    {
        $config = cache('monsoon') ?? Config::get();
        $colors = $config['default']['colors'];
        $value = $color;

        if(Str::has($color, '-'))
        {
            $break = Str::break($color, '-');
            $color = $break[0];
            $shade = $break[1];

            if(array_key_exists($color, $colors))
            {
                $value = $colors[$color];

                if(is_array($value))
                {
                    if(array_key_exists($shade, $value))
                    {
                        $value = $value[$shade];
                    }
                }
            }
        }
        else
        {
            if(array_key_exists($color, $colors))
            {
                $value = $colors[$color];
            }
        }

        $this->onfocus = "this.style.borderColor='" . $value . "'";
        $this->onblur = "this.removeAttribute('style')";
    }

}