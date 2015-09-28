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
         'template_map' => include __DIR__  .'/../template_map.php',
    ],
];
