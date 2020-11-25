<?php

namespace Voyager\App;

abstract class Middleware
{
    /**
     * Store request object.
     * 
     * @var \Voyager\App\Request
     */

    private $request;

    /**
     * Create new middleware instance.
     * 
     * @param   \Voyager\App\Request
     * @return  void
     */
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Test middleware logic.
     * 
     * @return  void
     */

    public function test()
    {
        $this->handle($this->request);
        app()->proceed = true;
        app()->index++;
    }

    /**
     * Handle request logic from parent class.
     * 
     * @param   \Voyager\App\Request $request
     * @return  mixed 
     */

    protected function handle(Request $request) {}

}