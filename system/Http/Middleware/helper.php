<?php

namespace App\Middleware
{
    /**
     * Proceed to the next middleware.
     * 
     * @return  void
     */

    if(!function_exists('proceed'))
    {
        function proceed()
        {
            app()->proceed = true;
            app()->index++;
        }
    }

    /**
     * Bypass middleware testing.
     * 
     * @return  void
     */

    if(!function_exists('bypass'))
    {
        function bypass()
        {
            app()->bypass = true;
        }
    }

}