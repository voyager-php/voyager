<?php

return [

    /**
     * Middlewares that will run in each request.
     * 
     * @var array
     */

    'bootstrap' => [
        \App\Middleware\MaintenanceModeMiddleware::class,
        \App\Middleware\RequestMethodMiddleware::class,
        \App\Middleware\ErrorLimiterMiddleware::class,
        \App\Middleware\RateLimiterMiddleware::class,
        \App\Middleware\HttpsRedirectionMiddleware::class,
        \App\Middleware\AuthenticationMiddleware::class,
        \App\Middleware\PermissionMiddleware::class,
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
        'expire'            => \App\Middleware\RouteExpirationMiddleware::class,
        'commence'          => \App\Middleware\CommenceAccessMiddleware::class,
        'location'          => \App\Middleware\BlockLocationMiddleware::class,
    ],

];