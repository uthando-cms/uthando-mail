<?php
namespace UthandoMail\Hydrator;

use UthandoCommon\Hydrator\AbstractHydrator;
use UthandoCommon\Hydrator\Strategy\DateTime as DateTimeStrategy;

class MailQueue extends AbstractHydrator
{
    public Function __construct()
    {
    	parent::__construct();
    
    	$this->addStrategy('dateCreated', new DateTimeStrategy());
    }
    
    /**
     * @param \UthandoMail\Model\MailQueue $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'mailQueueId' => $object->getMailQueueId(),
            'recipient' => $object->getRecipient(),
            'sender' => $object->getSender(),
            'subject' => $object->getSubject(),
            'bodyText' => $object->getBodyText(),
            'bodyHtml' => $object->getBodyHtml(),
            'dateCreated' => $object->getDateCreated(),
        ];
        
    }
}
