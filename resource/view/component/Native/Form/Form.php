<?php

namespace Components\Native\Form;

use Voyager\App\Components;

class Form extends Components
{
    /**
     * Form component html attributes.
     * 
     * @var array
     */

    protected $prop = [
        'name'              => null,
        'method'            => 'GET',
        'action'            => null,
        'formdata'          => false,
        'onsubmit'          => null,
        'onreset'           => null,
        'redirection'       => null,
    ];

    /**
     * Form component internal variables.
     * 
     * @var array
     */

    protected $data = [
        'verb'              => 'GET',
        'enctype'           => 'application/x-www-form-urlencode',
    ];

    /**
     * Set the real request method to use.
     * 
     * @param   string $method
     * @return  void
     */

    protected function method(string $method)
    {
        $method = strtolower($method);

        if($method === 'get')
        {
            $this->set('verb', 'GET');
        }
        else
        {
            $this->set('verb', 'POST');
        }
    }

    /**
     * Set enctype to formdata.
     * 
     * @param   bool $enable
     * @return  void
     */

    protected function formdata(bool $enable)
    {
        if($enable)
        {
            if($this->get('verb') === 'GET')
            {
                $this->method = 'post';
            }

            $this->set('enctype', 'multipart/form-data');
        }
        else
        {
            $this->set('enctype', 'application/x-www-form-urlencode');
        }
    }

}