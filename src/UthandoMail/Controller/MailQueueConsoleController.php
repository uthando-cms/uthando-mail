<?php

namespace UthandoMail\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

class MailQueueConsoleController extends AbstractActionController
{
    public function sendAction()
    {
    	$request = $this->getRequest();
    
    	if (!$request instanceof ConsoleRequest){
    		throw new \RuntimeException('You can only use this action from a console!');
    	}
    	
    	$sl = $this->getServiceLocator();
    	$mailQueueService = $sl->get('UthandoMail\Service\MailQueue');
    	
    	$mailsSent = $mailQueueService->processQueue();
    
    	return "No of mails sent: " . $mailsSent . "\r\n";
    }
}
