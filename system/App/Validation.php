<?php

namespace Voyager\App;

use Voyager\Http\Validation\Validate;
use Voyager\Util\Arr;

abstract class Validation
{
    /**
     * Store request object.
     * 
     * @var \Voyager\App\Request
     */

    private $request;

    /**
     * Store validation errors.
     * 
     * @var \Voyager\Util\Arr
     */

    private $errors;

    /**
     * Methods that are not parameters.
     * 
     * @var array
     */

    private $exclude = [
        '__construct',
        'getParameterValue',
        'success',
        'getErrors',
        'test',
    ];

    /**
     * Create new validation instance.
     * 
     * @param   \Voyager\App\Request $request
     * @return  void
     */

    public function __construct(Request $request)
    {
        $this->errors = new Arr();
        $this->request = $request;
        $this->test();
    }

    /**
     * Test parameters defined by method names.
     * 
     * @return void
     */

    private function test()
    {
        $methods = get_class_methods($this);
        
        for($i = 0; $i <= (sizeof($methods) - 1); $i++)
        {
            $method = $methods[$i];

            if(!in_array($method, $this->exclude))
            {
                if(method_exists($this, $method))
                {
                    $value = $this->getParameterValue($method);
                    $parameter = new Parameter($value, $method);
                    $this->{$method}($parameter);
                    
                    $data = $parameter->data();
                    $validate = new Validate($data->type, $parameter->getValue(), $data->optional);
                    
                    if(!$validate->isValid())
                    {
                        $message = $parameter->getErrorMessage($validate->getErrorCode());
                        
                        if(!is_null($message))
                        {
                            $this->errors->set($method, $message);
                        }
                        else
                        {
                            $this->errors->set($method, 'Please enter a valid ' . $method . '.');
                        }
                    }
                    else
                    {
                        if(!is_null($data->error))
                        {
                            $this->errors->set($method, $data->error);
                        }
                    }
                }
            }
        }
    }

    /**
     * Return true if no errors found during validation.
     * 
     * @return  bool
     */

    public function success()
    {
        return $this->errors->empty();
    }

    /**
     * Return array of errors encountered.
     * 
     * @return  array
     */

    public function getErrors()
    {
        return $this->errors->get();
    }

    /**
     * Return parameter values to validate.
     * 
     * @param   string $key
     * @return  mixed
     */

    protected function getParameterValue(string $key)
    {
        $request = $this->request;
        
        if(!is_null($request->get($key)))
        {
            return $request->get($key);
        }
        else if(!is_null($request->post($key)))
        {
            return $request->post($key);
        }
        else if(!is_null($request->{$key}))
        {
            return $request->{$key};
        }
    }

}