<?php

namespace UthandoMailQueue\Controller;

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
    
    	return "Sending mail queue\r\n";
    }
}
