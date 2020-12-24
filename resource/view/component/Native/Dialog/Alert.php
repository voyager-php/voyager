<?php

namespace Components\Native\Dialog;

use Voyager\App\Components;

class Alert extends Components
{
    /**
     * Alert box component html properties.
     * 
     * @var array
     */

    protected $prop = [
        'size'              => 'medium',
        'bgcolor'           => 'blue-600',
        'color'             => 'white',
        'icon'              => null,
        'dismiss'           => true,
        'display'           => true,
        'scheme'            => 'primary',
        'href'              => null,
    ];

    /**
     * Store component's internal variables.
     * 
     * @var array
     */

    protected $data = [
        'background'        => 'bg-blue-600',
        'text_color'        => 'text-white',
        'visibility'        => null,
        'onclick'           => null,
        'cursor'            => null,
    ];

    /**
     * Set alert dialog box background color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function bgcolor(string $color)
    {
        $this->set('background', 'bg-' . $color);
    }

    /**
     * Set alert dialog box text color.
     * 
     * @param   string $color
     * @return  void
     */

    protected function color(string $color)
    {
        $this->set('text_color', 'text-' . $color);
    }

    /**
     * Set the visibility of the alert dialog box.
     * 
     * @param   bool $display
     * @return  void
     */

    protected function display(bool $display)
    {
        if(!$display)
        {
            $this->set('visibility', 'hidden');
        }
        else
        {
            $this->set('visibility', null);
        }
    }

    /**
     * Set redirection path.
     * 
     * @param   string $url
     * @return  void
     */

    protected function href(string $url)
    {
        $this->set('cursor', 'cursor-pointer');
        $this->set('onclick', 'window.location.href=\'' . $url . '\';');
    }

    /**
     * Set alert box color scheme.
     * 
     * @param   string $scheme
     * @return  void
     */

    protected function scheme(string $scheme)
    {
        $scheme = strtolower($scheme);

        if($scheme === 'danger')
        {
            $this->bgcolor = 'red-500';
            $this->color = 'white';
        }
        else if($scheme === 'info')
        {
            $this->bgcolor = 'blue-400';
            $this->color = 'white';
        }
        else if($scheme === 'warning')
        {
            $this->bgcolor = 'yellow-600';
            $this->color = 'white';
        }
        else if($scheme === 'success')
        {
            $this->bgcolor = 'green-600';
            $this->color = 'white';
        }
        else if($scheme === 'primary')
        {
            $this->bgcolor = 'blue-500';
            $this->color = 'white';
        }
        else if($scheme === 'secondary')
        {
            $this->bgcolor = 'gray-400';
            $this->color = 'gray-800';
        }
    }

}