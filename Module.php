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
        return include __DIR__ . '/config/config.php';
    }

    public function getControllerConfig()
    {
        return include __DIR__ . '/config/controller.config.php';
    }
    
    public function getFormElementConfig()
    {
        return include __DIR__ . '/config/formElement.config.php';
    }

    public function getHydratorConfig()
    {
        return include __DIR__ . '/config/hydrator.config.php';
    }

    public function getInputFilterConfig()
    {
        return include __DIR__ . '/config/inputFilter.config.php';
    }
    
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    public function getUthandoMapperConfig()
    {
        return include __DIR__ . '/config/mapper.config.php';
    }

    public function getUthandoModelConfig()
    {
        return include __DIR__ . '/config/model.config.php';
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
