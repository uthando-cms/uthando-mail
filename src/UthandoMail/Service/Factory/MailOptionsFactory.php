<?php
namespace UthandoMail\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UthandoMail\Options\MailOptions;

class MailOptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $options = (isset($config['uthando_mail'])) ? $config['uthando_mail'] : [];
    
        $options = new MailOptions($options);
    
        return $options;
    }
}
