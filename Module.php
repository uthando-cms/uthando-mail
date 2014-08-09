<?php

namespace UthandoMail;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Mvc\MvcEvent;
use UthandoMail\Event\MailListener;

class Module implements 
AutoloaderProviderInterface,
ConsoleUsageProviderInterface,
ConsoleBannerProviderInterface
{
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();
        
        $eventManager->attachAggregate(new MailListener());
    }
    
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
    	return [
            'invokables' => [
                'UthandoMail\Hydrator\MailQueue' => 'UthandoMail\Hydrator\MailQueue',
                'UthandoMail\InputFilter\MailQueue' => 'UthandoMail\InputFilter\MailQueue',
            	'UthandoMail\Mail\HtmlMessage' => 'UthandoMailQueue\Mail\HtmlMessage',
            	'UthandoMail\Mapper\MailQueue' => 'UthandoMail\Mapper\MailQueue',
            	'UthandoMail\Model\MailQueue' => 'UthandoMail\Model\MailQueue',
            	'UthandoMail\Service\MailQueue' => 'UthandoMail\Service\MailQueue',
            ],
            'factories' => [
                'UthandoMail\Service\Mail' => 'UthandoMail\Service\Factory\MailFactory',
                'UthandoMail\Options\MailQueueOptions' => 'UthandoMail\Service\Factory\MailQueueOptionsFactory',
    	   ],
    	];
    }
    
    public function getConsoleUsage(Console $console)
    {
    	return [
    		'mailqueue send' => 'send the next batch of mail in the mail queue',
    	];
    }
    
    public function getConsoleBanner(Console $console){
        return
        "==------------------------------------------------------==\n" .
        "     Welcome to Uthando Mail ZF2 Console-enabled app      \n" .
        "==------------------------------------------------------==\n" .
        "Version 1.0\n";
    }
}
