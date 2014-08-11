<?php
namespace UthandoMail\Model;

use UthandoCommon\Model\Model;
use UthandoCommon\Model\DateCreatedTrait;
use Zend\Mail\Address;
use UthandoCommon\Model\ModelInterface;

class MailQueue implements ModelInterface
{
    use Model;
    use DateCreatedTrait;
    
    protected $mailQueueId;
    protected $recipient;
    protected $sender;
    protected $subject;
    protected $body;
    protected $layout;
    protected $transport = 'default';
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
	 */
	public function setBody($body)
	{
		$this->body = $body;
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
	 */
	public function setPriority($priority)
	{
		$this->priority = $priority;
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
	 */
	public function setTransport($transport)
	{
		$this->transport = $transport;
		return $this;
	}

}
