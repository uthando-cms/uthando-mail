<?php
namespace UthandoMail\Hydrator;

use UthandoCommon\Hydrator\AbstractHydrator;
use UthandoCommon\Hydrator\Strategy\DateTime as DateTimeStrategy;
use UthandoCommon\Hydrator\Strategy\Null as NullStrategy;
use UthandoCommon\Hydrator\Strategy\Serialize;

class MailQueue extends AbstractHydrator
{
    public Function __construct()
    {
    	parent::__construct();
    	
    	$serialize = new Serialize();
    
    	$this->addStrategy('dateCreated', new DateTimeStrategy());
    	$this->addStrategy('layout', new NullStrategy());
    	$this->addStrategy('body', $serialize);
    	$this->addStrategy('sender', $serialize);
    	$this->addStrategy('recipient',$serialize);
    	
    }
    
    /**
     * @param \UthandoMail\Model\MailQueue $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'mailQueueId'   => $object->getMailQueueId(),
            'recipient'     => $this->extractValue('recipient', $object->getRecipient()),
            'sender'        => $this->extractValue('sender', $object->getSender()),
            'subject'       => $object->getSubject(),
            'body'          => $this->extractValue('body', $object->getBody()),
            'layout'        => $this->extractValue('layout', $object->getLayout()),
            'transport'     => $object->getTransport(),
            'priority'      => $object->getPriority(),
            'dateCreated'   => $this->extractValue('dateCreated', $object->getDateCreated()),
        ];
        
    }
}
