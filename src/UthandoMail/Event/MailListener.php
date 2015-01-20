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
        $data = $e->getParams();
        $sl = $e->getTarget()->getServiceLocator();
        
        /* @var $sendMail \UthandoMail\Service\Mail */
        $sendMail = $sl->get('UthandoMail\Service\Mail');
        
        if (isset($data['layout'])) {
            $sendMail->setLayout($data['layout']);
            
            if (isset($data['layout_params'])) {
                $sendMail->getLayout()->setVariables($data['layout_params']);
            }
        }
        
        $subject = $data['subject'];
        $transport = $data['transport'];
        $body = $data['body'];

        $recipient = (isset($data['recipient'])) ? $data['recipient'] : $sendMail->getOption('AddressList')[$transport];

        if (is_array($recipient)) {
            $to = $sendMail->createAddress($recipient['address'], $recipient['name']);
        } else {
            $to = $recipient;
        }
        
        $sender = (isset($data['sender'])) ? $data['sender'] : $sendMail->getOption('AddressList')[$transport];
        
        if (is_array($sender)) {
            $from = $sendMail->createAddress($sender['address'], $sender['name']);
        } else {
            $from = $sender;
        }
        
        $message = $sendMail->compose($body)
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject);
        
        $sendMail->send($message, $transport);
        
    }
    
    public function queueMail(Event $e)
    {
        $sl = $e->getTarget()->getServiceLocator();
        $data = $e->getParams();
        
        /* @var $mailQueue \UthandoMail\Service\MailQueue */
        $mailQueue = $sl->get('UthandoMail\Service\MailQueue');
        $hydrator = $sl->get('UthandoMail\Hydrator\MailQueue');
        $model = $sl->get('UthandoMail\Model\MailQueue');
        
        $model = $hydrator->hydrate($data, $model);
        
        $mailQueue->save($model);
    }
}
