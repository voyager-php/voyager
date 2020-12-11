<?php

return [

    /**
     * Middlewares that will run in each request.
     * 
     * @var array
     */

    'bootstrap' => [
        \App\Middleware\RequestMethodMiddleware::class,
        \App\Middleware\ErrorLimiterMiddleware::class,
        \App\Middleware\RateLimiterMiddleware::class,
        // \App\Middleware\HttpsRedirectionMiddleware::class,
        \App\Middleware\AuthenticationMiddleware::class,
    ],

    /**
     * Group of middlewares. 
     * 
     * @var array
     */

    'groups' => [

        'web' => [

        ],

        'api' => [
            
        ]

    ],

    /**
     * Middlewares that can be assigned to routes.
     * 
     * @var array
     */

    'middlewares' => [
        'cors'              => \App\Middleware\CrossOriginMiddleware::class,
        'ajax'              => \App\Middleware\AjaxRequestMiddleware::class,
        'csrf'              => \App\Middleware\CSRFTokenMiddleware::class,
        'ip'                => \App\Middleware\IPBlockerMiddleware::class,
        'validation'        => \App\Middleware\ValidationMiddleware::class,
        'accessibility'     => \App\Middleware\RouteAccessibilityMiddleware::class,
        'location'          => \App\Middleware\BlockLocationMiddleware::class,
        'permission'        => \App\Middleware\PermissionMiddleware::class,
    ],

];