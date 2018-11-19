<?php

use UthandoMail\Controller\MailQueueController;

return [
    'uthando_user' => [
        'acl' => [
            'roles' => [
                'admin'        => [
                    'privileges'    => [
                        'allow' => [
                            'controllers' => [
                                MailQueueController::class => ['action' => 'all'],
                            ],
                        ],
                    ],
                ],
            ],
            'resources' => [
                MailQueueController::class,
            ],
        ],
    ],
];
