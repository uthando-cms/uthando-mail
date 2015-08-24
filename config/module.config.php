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
    'controllers' => [
        'invokables' => [
            'UthandoMail\Controller\MailQueue'          => 'UthandoMail\Controller\MailQueueController',
            'UthandoMail\Controller\MailQueueConsole'   => 'UthandoMail\Controller\MailQueueConsoleController',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'UthandoMailTransportList' => 'UthandoMail\Form\Element\MailTransportList',
        ],
    ],
    'hydrators' => [
        'invokables' => [
            'UthandoMailMailQueue' => 'UthandoMail\Hydrator\MailQueue',
        ],
    ],
    'input_filters' => [
        'invokables' => [
            'UthandoMailMailQueue' => 'UthandoMail\InputFilter\MailQueue',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'UthandoMail\Mail\HtmlMessage'      => 'UthandoMailQueue\Mail\HtmlMessage',
            'UthandoMail\Service\MailQueue'     => 'UthandoMail\Service\MailQueue',
        ],
        'factories' => [
            'UthandoMail\Service\Mail'          => 'UthandoMail\Service\Factory\MailFactory',
            'UthandoMail\Options\MailOptions'   => 'UthandoMail\Service\Factory\MailOptionsFactory',
        ],
    ],
    'uthando_mappers' => [
        'invokables' => [
            'UthandoMailMailQueue' => 'UthandoMail\Mapper\MailQueue',
        ],
    ],
    'uthando_models' => [
        'invokables' => [
            'UthandoMailMailQueue' => 'UthandoMail\Model\MailQueue',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'UthandoMailQueue' => __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'mail-queue' => [
                        'type'    => 'Segment',
                        'options' => [
                            // Change this to something specific to your module
                            'route'    => '/mail-queue',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'UthandoMail\Controller',
                                'controller'    => 'MailQueue',
                                'action'        => 'index',
                                'force-ssl'     => 'ssl',
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
                                        'force-ssl'     => 'ssl'
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
        							    'force-ssl'     => 'ssl'
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
    'navigation' => [
        'admin' => [
            'mail-queue' => [
                'label'     => 'Mail Queue',
                'action'    => 'index',
                'route'     => 'admin/mail-queue',
                'resource'  => 'menu:admin'
            ],
        ],
    ],
];
