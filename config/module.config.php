<?php

use UthandoMail\Controller\MailQueueConsoleController;
use UthandoMail\Controller\MailQueueController;
use UthandoMail\Options\MailOptions;
use UthandoMail\Service\Factory\MailFactory;
use UthandoMail\Service\Factory\MailOptionsFactory;
use UthandoMail\Service\Mail;
use UthandoMail\Service\MailQueueService;
use UthandoMail\View\Helper\MailAddress;

return [
    'controllers' => [
        'invokables' => [
            MailQueueController::class          => MailQueueController::class,
            MailQueueConsoleController::class   => MailQueueConsoleController::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Mail::class         => MailFactory::class,
            MailOptions::class  => MailOptionsFactory::class,
        ],
    ],
    'uthando_services' => [
        'invokables' => [
            MailQueueService::class => MailQueueService::class
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'UthandoMailAddress' => MailAddress::class,
        ],
        'invokables' => [
            MailAddress::class => MailAddress::class
        ],
    ],
    'view_manager' => [
         'template_map' => include __DIR__  .'/../template_map.php',
    ],
];
