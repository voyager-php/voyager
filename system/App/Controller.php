<?php

namespace Voyager\App;

abstract class Controller
{
    /**
     * Store the response data.
     * 
     * @var mixed
     */

    private $response;

    /**
     * Store database resource class.
     * 
     * @var string
     */

    protected $resource;

    /**
     * Store database model.
     * 
     * @var \Voyager\Database\Model
     */

    protected $model;

    /**
     * Create new controller instance.
     * 
     * @param   array
     * @return  void
     */

    public function __construct(array $route)
    {
        $method = $route['method'];

        if(method_exists($this, $method))
        {
            $resource = $this->resource;

            if(!is_null($resource))
            {
                $model = new $this->resource($this->resource);
                $this->model = $model->service();
            }

            $this->response = $this->{$method}(new Request($route));
        }
    }

    /**
     * Return controller response.
     * 
     * @return  mixed
     */

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function index(Request $request) {}

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function create(Request $request) {}

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function store(Request $request) {}

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function show(Request $request) {}

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function edit(Request $request) {}

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function update(Request $request) {}

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed
     */

    protected function destroy(Request $request) {}

}