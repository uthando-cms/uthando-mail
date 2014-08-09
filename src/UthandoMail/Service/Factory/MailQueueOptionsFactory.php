<?php
namespace UthandoMail\Service\Factory;

use UthandoMail\Options\MailQueueOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailQueueOptionsFactory implements FactoryInterface
{
    
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$config = $serviceLocator->get('config');
		$options = (isset($config['uthando_mail']['mail_queue'])) ? $config['uthando_mail']['mail_queue'] : [];
		
		return new MailQueueOptions($options);
	}
}
