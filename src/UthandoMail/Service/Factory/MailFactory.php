<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Service\Factory
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Service\Factory;

use UthandoMail\Options\MailOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
use UthandoMail\Service\Mail;

/**
 * Class MailFactory
 *
 * @package UthandoMail\Service\Factory
 */
class MailFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Mail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('UthandoMail\Options\MailOptions');

        $options = new MailOptions($options);
        $view = $this->getRenderer($serviceLocator);

        $mailService = new Mail($view, $options);

        return $mailService;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array|object|PhpRenderer
     */
    protected function getRenderer(ServiceLocatorInterface $serviceLocator)
    {
        // Check if a view renderer is available and return it
        if ($serviceLocator->has('ViewRenderer')) {
            return $serviceLocator->get('ViewRenderer');
        }

        // Create new PhpRenderer
        $renderer = new PhpRenderer();

        // Set the view script resolver if available
        if ($serviceLocator->has('Zend\View\Resolver\AggregateResolver')) {
            $renderer->setResolver(
                $serviceLocator->get('Zend\View\Resolver\AggregateResolver')
            );
        }

        // Set the view helper manager if available
        if ($serviceLocator->has('ViewHelperManager')) {
            $renderer->setHelperPluginManager(
                $serviceLocator->get('ViewHelperManager')
            );
        }

        // Return the new PhpRenderer
        return $renderer;
    }
}
