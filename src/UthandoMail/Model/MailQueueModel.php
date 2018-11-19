<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Model
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Model;

use UthandoCommon\Model\Model;
use UthandoCommon\Model\DateCreatedTrait;
use Zend\Mail\Address;
use UthandoCommon\Model\ModelInterface;

/**
 * Class MailQueue
 *
 * @package UthandoMail\Model
 */
class MailQueueModel implements ModelInterface
{
    use Model;
    use DateCreatedTrait;

    /**
     * @var int
     */
    protected $mailQueueId;

    /**
     * @var string|array|Address
     */
    protected $recipient;

    /**
     * @var string|array|Address
     */
    protected $sender;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $layout;

    /**
     * @var string
     */
    protected $transport = 'default';

    /**
     * @var string
     */
    protected $renderer = 'ViewRenderer';

    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * @return int $mailQueueId
     */
    public function getMailQueueId()
    {
        return $this->mailQueueId;
    }

    /**
     * @param int $mailQueueId
     * @return $this
     */
    public function setMailQueueId($mailQueueId)
    {
        $this->mailQueueId = $mailQueueId;
        return $this;
    }

    /**
     * @return string $recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string|array|Address $recipient
     * @return $this
     */
    public function setRecipient($recipient)
    {
        if (is_array($recipient)) {
            $recipient = new Address($recipient['address'], $recipient['name']);
        }

        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return string $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string|array|Address $sender
     * @return $this
     */
    public function setSender($sender)
    {
        if (is_array($sender)) {
            $sender = new Address($sender['address'], $sender['name']);
        }

        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string $subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string|object $body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string|object $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string $layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return string $transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param string $transport
     * @return $this
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string $renderer
     * @return $this
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return number $priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param number $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }
}
