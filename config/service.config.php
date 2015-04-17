<?php

return [
    'invokables' => [
        'UthandoMail\Mail\HtmlMessage' => 'UthandoMailQueue\Mail\HtmlMessage',
        'UthandoMail\Service\MailQueue' => 'UthandoMail\Service\MailQueue',
    ],
    'factories' => [
        'UthandoMail\Service\Mail'          => 'UthandoMail\Service\Factory\MailFactory',
        'UthandoMail\Options\MailOptions'   => 'UthandoMail\Service\Factory\MailOptionsFactory',
    ],
];