<?php

return [

    /**
     * The default users trough the seeds. only the First and lastname is required because the other data is filled up
     * with the default pass that is 'pasword'. And your email is in the following format.
     *
     * <firstname>@<application mail domain>
     */

    'users' => [
        ['Tim', 'Joosten'], ['Sara', 'Landuyt'], ['Tom', 'Manheaghe']
    ],

    /**
     * The 2FA config variable where u currently one can define if you want to use the 2FA authentication or not.
     */

    '2fa' => [
        'enabled' => true,
    ],
];
