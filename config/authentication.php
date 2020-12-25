<?php

return [

    /** 
     * Redirect request to this route if unauthenticated. 
     * 
     * @var string
     */

    'redirection' => url('/login'),

    /**
     * Type of users.
     * 
     * @var array
     */

    'users' => [
        'superadmin'            => 0,
        'admin'                 => 1,
        'member'                => 2,
        'guest'                 => 3,
    ],

    /** 
     * Number of hours before authentication cookie expired.
     * 
     * @var int
     */

    'expiration' => 48,

];