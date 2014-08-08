<?php
namespace UthandoMail\Hydrator;

use UthandoCommon\Hydrator\AbstractHydrator;

class MailQueue extends AbstractHydrator
{
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
