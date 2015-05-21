<?php

namespace UthandoMail;

use UthandoMail\Event\MailListener;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\MvcEvent;


class Module implements ConsoleUsageProviderInterface
{
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();
        
        $eventManager->attachAggregate(new MailListener());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getConsoleUsage(Console $console)
    {
    	return [
    		'mailqueue send' => 'send the next batch of mail in the mail queue',
    	];
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
        ];
    }
}
