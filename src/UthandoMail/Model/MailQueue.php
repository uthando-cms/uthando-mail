<?php
namespace UthandoMail\Model;

use UthandoCommon\Model\Model;
use UthandoCommon\Model\DateCreatedTrait;

class MailQueue
{
    use Model;
    use DateCreatedTrait;
    
    protected $mailQueueId;
    protected $recipient;
    protected $sender;
    protected $subject;
    protected $bodyText;
    protected $bodyHtml;
    
	/**
	 * @return the $mailQueueId
	 */
	public function getMailQueueId()
	{
		return $this->mailQueueId;
	}

	/**
	 * @param field_type $mailQueueId
	 */
	public function setMailQueueId($mailQueueId)
	{
		$this->mailQueueId = $mailQueueId;
		return $this;
	}

	/**
	 * @return the $recipient
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}

	/**
	 * @param field_type $recipient
	 */
	public function setRecipient($recipient)
	{
		$this->recipient = $recipient;
		return $this;
	}

	/**
	 * @return the $sender
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * @param field_type $sender
	 */
	public function setSender($sender)
	{
		$this->sender = $sender;
		return $this;
	}

	/**
	 * @return the $subject
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param field_type $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}

	/**
	 * @return the $bodyText
	 */
	public function getBodyText()
	{
		return $this->bodyText;
	}

	/**
	 * @param field_type $bodyText
	 */
	public function setBodyText($bodyText)
	{
		$this->bodyText = $bodyText;
		return $this;
	}

	/**
	 * @return the $bodyHtml
	 */
	public function getBodyHtml()
	{
		return $this->bodyHtml;
	}

	/**
	 * @param field_type $bodyHtml
	 */
	public function setBodyHtml($bodyHtml)
	{
		$this->bodyHtml = $bodyHtml;
		return $this;
	}

}
