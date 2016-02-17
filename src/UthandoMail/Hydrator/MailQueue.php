<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Hydrator
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Hydrator;

use UthandoCommon\Hydrator\AbstractHydrator;
use UthandoCommon\Hydrator\Strategy\DateTime as DateTimeStrategy;
use UthandoCommon\Hydrator\Strategy\Serialize;

/**
 * Class MailQueue
 *
 * @package UthandoMail\Hydrator
 */
class MailQueue extends AbstractHydrator
{
    /**
     * Constructor
     */
    public Function __construct()
    {
        parent::__construct();

        $serialize = new Serialize();

        $this->addStrategy('dateCreated', new DateTimeStrategy());
        $this->addStrategy('layout', $serialize);
        $this->addStrategy('body', $serialize);
        $this->addStrategy('sender', $serialize);
        $this->addStrategy('recipient', $serialize);

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
            'renderer'      => $object->getRenderer(),
            'priority'      => $object->getPriority(),
            'dateCreated'   => $this->extractValue('dateCreated', $object->getDateCreated()),
        ];

    }
}
