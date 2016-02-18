<?php
return [
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
            'UthandoMailQueue' => 'UthandoMail\Hydrator\MailQueue',
        ],
    ],
    'input_filters' => [
        'invokables' => [
            'UthandoMailQueue' => 'UthandoMail\InputFilter\MailQueue',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'UthandoMail\Service\Mail'          => 'UthandoMail\Service\Factory\MailFactory',
            'UthandoMail\Options\MailOptions'   => 'UthandoMail\Service\Factory\MailOptionsFactory',
        ],
    ],
    'uthando_mappers' => [
        'invokables' => [
            'UthandoMailQueue' => 'UthandoMail\Mapper\MailQueue',
        ],
    ],
    'uthando_models' => [
        'invokables' => [
            'UthandoMailQueue' => 'UthandoMail\Model\MailQueue',
        ],
    ],
    'uthando_services' => [
        'invokables' => [
            'UthandoMailQueue'     => 'UthandoMail\Service\MailQueue',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'UthandoMailAddress'    => 'UthandoMail\View\Helper\MailAddress',
        ],
    ],
    'view_manager' => [
         'template_map' => include __DIR__  .'/../template_map.php',
    ],
];
