<?php

namespace Components\Button;

use Voyager\App\Components;

class Button extends Components
{
    /**
     * Button component html properties.
     * 
     * @var array
     */

    protected $prop = [
        'name'                  => null,
        'bgcolor'               => 'blue-500',
        'hover'                 => 'blue-600',
        'color'                 => 'white',
        'size'                  => 'medium',
        'occupy'                => false,
        'disabled'              => false,
        'title'                 => null,
        'style'                 => null,
        'scheme'                => 'primary',
        'onclick'               => null,
        'bold'                  => true,
        'rounded'               => true,
    ];

    /**
     * Store component's internal variables.
     * 
     * @var array
     */

    protected $data = [
        'default'               => 'btn-md',
        'background'            => 'bg-blue-500',
        'hover_color'           => 'hover:bg-blue-600',
        'text_color'            => 'text-white',
        'weight'                => 'font-medium',
        'width'                 => null,
        'border_radius'         => 'rounded',
    ];

    /**
     * Set button's background color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function bgcolor(string $color)
    {
        $this->set('background', 'bg-' . $color);
        $this->set('hover_color', null);
    }

    /**
     * Set button's hover background color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function hover(string $color)
    {
        $this->set('hover_color', 'hover:bg-' . $color);
    }

    /**
     * Set button's text color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function color(string $color)
    {
        $this->set('text_color', 'text-' . $color);
    }

    /**
     * Set button text weight to bold.
     * 
     * @param   bool $bold
     * @return  void
     */

    protected function bold(bool $bold)
    {
        if($bold)
        {
            $this->set('weight', 'font-medium');
        }
        else
        {
            $this->set('weight', null);
        }
    }

    /**
     * Make button corners rounded.
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
            $this->set('border_radius', null);
        }
    }

    /**
     * Set button component height and font size.
     * 
     * @param   string $size
     * @return  void
     */

    protected function size(string $size)
    {
        $size = strtolower($size);
        
        if($size === 'small')
        {
            $this->set('default', 'btn-sm');
        }
        else if($size === 'medium')
        {
            $this->set('default', 'btn-md');
        }
        else if($size === 'large')
        {
            $this->set('default', 'btn-lg');
        }
    }

    /**
     * Set button to occupy parent element.
     * 
     * @param   bool $occupy
     * @return  void
     */

    protected function occupy(bool $occupy)
    {
        if($occupy)
        {
            $this->set('width', 'w-full');
        }
        else
        {
            $this->set('width', null);
        }
    }

    /**
     * Set button bgcolor when disabled.
     * 
     * @param   bool $disabled
     * @return  void
     */

    protected function disabled(bool $disabled)
    {
        if($disabled)
        {
            $scheme = strtolower($this->scheme);

            if($scheme !== 'primary')
            {
                if($scheme === 'danger')
                {
                    $this->bgcolor = 'red-300';
                    $this->color = 'white';
                }
                else if($scheme === 'info')
                {
                    $this->bgcolor = 'blue-200';
                    $this->color = 'white';
                }
                else if($scheme === 'warning')
                {
                    $this->bgcolor = 'yellow-300';
                    $this->color = 'white';
                }
                else if($scheme === 'success')
                {
                    $this->bgcolor = 'green-200';
                    $this->color = 'white';
                }
                else if($scheme === 'secondary')
                {
                    $this->bgcolor = 'gray-200';
                    $this->color = 'gray-400';
                }
            }
            else
            {
                $this->bgcolor = 'blue-200';
                $this->color = 'white';
            }
        }
    }

    /**
     * Set button color scheme.
     * 
     * @param   string $scheme
     * @return  void
     */

    protected function scheme(string $scheme)
    {
        $scheme = strtolower($scheme);

        if($scheme === 'danger')
        {
            $this->bgcolor = 'red-400';
            $this->hover = 'red-500';
            $this->color = 'white';
        }
        else if($scheme === 'info')
        {
            $this->bgcolor = 'blue-400';
            $this->hover = 'blue-500';
            $this->color = 'white';
        }
        else if($scheme === 'warning')
        {
            $this->bgcolor = 'yellow-500';
            $this->hover = 'yellow-600';
            $this->color = 'white';
        }
        else if($scheme === 'success')
        {
            $this->bgcolor = 'green-600';
            $this->hover = 'green-700';
            $this->color = 'white';
        }
        else if($scheme === 'primary')
        {
            $this->bgcolor = 'blue-500';
            $this->hover = 'blue-600';
            $this->color = 'white';
        }
        else if($scheme === 'secondary')
        {
            $this->bgcolor = 'gray-300';
            $this->hover = 'gray-400';
            $this->color = 'gray-700';
        }
    }

}