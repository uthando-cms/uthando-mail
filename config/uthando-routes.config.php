<?php

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
                                'controller'    => 'MailQueue',
                                'action'        => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'edit' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/[:action[/id/[:id]]]',
                                    'constraints' => [
                                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'		=> '\d+'
                                    ],
                                    'defaults' => [
                                        'action'        => 'edit',
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
                            'controller' => 'MailQueueConsole',
                            'action' => 'send'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
