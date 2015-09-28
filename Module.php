<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail;

use UthandoMail\Event\MailListener;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;

/**
 * Class Module
 *
 * @package UthandoMail
 */
class Module implements ConsoleUsageProviderInterface
{
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        // Registering a listener at default priority, 1, which will trigger
        // after the ConfigListener merges config.
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
    }

    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        // Modify the configuration;
        if (isset($config['load_uthando_configs']) && true === $config['load_uthando_configs']) {
            $routes = include __DIR__ . '/config/uthando-routes.config.php';
            $acl = include __DIR__ . '/config/uthando-user.config.php';
            $navigation = include __DIR__ . '/config/uthando-navigation.config.php';
            $dompdfConfig = array_merge($routes, $navigation, $acl);
            $config = ArrayUtils::merge($config, $dompdfConfig);
        }

        // Pass the changed configuration back to the listener:
        $configListener->setMergedConfig($config);
    }

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
