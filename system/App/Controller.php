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