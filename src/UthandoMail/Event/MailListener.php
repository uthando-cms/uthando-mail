<?php
namespace UthandoMail\Event;

use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class MailListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;
    
    public function attach(EventManagerInterface $events)
    {
    	$events = $events->getSharedManager();
    	
    	$listeners = [
    	   'UthandoCommon\Service\AbstractService',
    	   'Zend\Mvc\Controller\AbstractActionController',
    	];
    
    	$this->listeners[] = $events->attach($listeners, 'mail.send', [$this, 'sendMail']);
    	$this->listeners[] = $events->attach($listeners, 'mail.queue', [$this, 'queueMail']);
    }
    
    public function sendMail(Event $e)
    {
        
    }
    
    public function queueMail(Event $e)
    {
        
    }
}
