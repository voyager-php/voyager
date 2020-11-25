<?php

namespace Voyager\App;

use Voyager\Util\Data\Collection;

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
     * @param   \Voyager\Util\Data\Collection $route
     * @return  void
     */

    public function __construct(Collection $route)
    {
        $method = $route->method;

        if(method_exists($this, $method))
        {
            $this->response = $this->{$method}(new Request($route->toArray()));
        }
        else
        {
            abort(404);
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