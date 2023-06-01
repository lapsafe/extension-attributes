<?php

return [

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'lapsafe.extension-attributes.cache',
        'store' => 'default',
    ],

    /**
     * Register Models that can use Extension Attributes
     */
    'models' => [
        //'Users' => 'App\Models\User',
    ]

];
