<?php

use UthandoMail\Controller\MailQueueConsoleController;
use UthandoMail\Controller\MailQueueController;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'mail-queue' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/mail-queue',
                            'defaults' => [
                                '__NAMESPACE__' => 'UthandoMail\Controller',
                                'controller'    => MailQueueController::class,
                                'action'        => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'edit' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/[:action]',
                                    'constraints' => [
                                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                        'action'        => 'delete',
                                    ],
                                ],
                            ],
                            'page' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/page/[:page]',
                                    'constraints' => [
                                        'page' => '\d+'
                                    ],
                                    'defaults' => [
                                        'action'        => 'list',
                                        'page'          => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'mail/queue/send' => [
                    'options' => [
                        'route' => 'mailqueue send',
                        'defaults' => [
                            '__NAMESPACE__' => 'UthandoMail\Controller',
                            'controller' => MailQueueConsoleController::class,
                            'action' => 'send'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
