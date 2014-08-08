<?php

namespace UthandoMail;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;

class Module implements 
AutoloaderProviderInterface,
ConsoleUsageProviderInterface,
ConsoleBannerProviderInterface
{
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
        "        Welcome to Mail ZF2 Console-enabled app           \n" .
        "==------------------------------------------------------==\n" .
        "Version 1.0\n";
    }
}
