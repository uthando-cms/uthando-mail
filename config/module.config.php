<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'UthandoMail\Controller\MailQueue' => 'UthandoMail\Controller\MailQueueController',
            'UthandoMail\Controller\MailQueueConsole' => 'UthandoMail\Controller\MailQueueConsoleController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'child-routes' => array(
                    'uthando-mail-queue' => array(
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
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:controller[/:action]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
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
    'view_manager' => array(
        'template_path_stack' => array(
            'UthandoMailQueue' => __DIR__ . '/../view',
        ),
    ),
);
