<?php

return [
    'navigation' => [
        'admin' => [
            'mail-queue' => [
                'label'     => 'Mail Queue',
                'params' => [
                    'icon' => 'fa-envelope',
                ],
                'params' => [
                    'icon' => 'fa-envelope',
                ],
                'action'    => 'index',
                'route'     => 'admin/mail-queue',
                'resource'  => 'menu:admin',
                'pages' => [
                    'list' => [
                        'label'     => 'Mail Queue List',
                        'action'    => 'index',
                        'route'     => 'admin/mail-queue',
                        'resource'  => 'menu:admin',
                        'visible'   => false,
                    ],
                ]
            ],
        ],
    ],
];
