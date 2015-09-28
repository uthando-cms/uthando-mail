<?php

return [
    'uthando_user' => [
        'acl' => [
            'roles' => [
                'admin'        => [
                    'privileges'    => [
                        'allow' => [
                            'controllers' => [
                                'UthandoMail\Controller\MailQueue' => ['action' => 'all'],
                            ],
                        ],
                    ],
                ],
            ],
            'resources' => [
                'UthandoMail\Controller\MailQueue',
            ],
        ],
    ],
];
