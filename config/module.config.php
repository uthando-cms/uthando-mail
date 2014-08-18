<?php
return array(
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
    'controllers' => array(
        'invokables' => array(
            'UthandoMail\Controller\MailQueue' => 'UthandoMail\Controller\MailQueueController',
            'UthandoMail\Controller\MailQueueConsole' => 'UthandoMail\Controller\MailQueueConsoleController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'child_routes' => array(
                    'mail-queue' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/mail-queue',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'UthandoMail\Controller',
                                'controller'    => 'MailQueue',
                                'action'        => 'index',
                                'force-ssl'     => 'ssl',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:action[/id/[:id]]]',
                                    'constraints' => array(
                                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id'		=> '\d+'
                                    ),
                                    'defaults' => array(
                                        'action'        => 'edit',
                                        'force-ssl'     => 'ssl'
                                    ),
                                ),
                            ),
                            'page' => array(
                            	'type'    => 'Segment',
                            	'options' => array(
                            		'route'    => '/page/[:page]',
                            		'constraints' => array(
                            			'page' => '\d+'
                            		),
                            		'defaults' => array(
                            			'action'        => 'list',
        								'page'          => 1,
        							    'force-ssl'     => 'ssl'
                            		),
                            	),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
		'router' => array(
			'routes' => array(
				'mail/queue/send' => array(
					'options' => array(
						'route' => 'mailqueue send',
						'defaults' => array(
							'__NAMESPACE__' => 'UthandoMail\Controller',
							'controller' => 'MailQueueConsole',
							'action' => 'send'
						),
					),
				),
			),
		),
    ),
    'navigation' => [
        'admin' => [
            'modules' => [
                'pages' => [
                    'mail-queue' => [
                        'label'     => 'Mail Queue',
                        'action'    => 'index',
                        'route'     => 'admin/mail-queue',
                        'resource'  => 'menu:admin'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => array(
        'template_path_stack' => array(
            'UthandoMailQueue' => __DIR__ . '/../view',
        ),
    ),
);
