<?php
namespace UthandoMail\Options;

use Zend\Stdlib\AbstractOptions;

class MailQueueOptions extends AbstractOptions
{
    /**
     * @var int
     */
    protected $maxAmountToSend;
    
	/**
	 * @return the $maxAmountToSend
	 */
	public function getMaxAmountToSend()
	{
		return $this->maxAmountToSend;
	}

	/**
	 * @param number $maxAmountToSend
	 */
	public function setMaxAmountTosend($maxAmountTosend)
	{
		$this->maxAmountToSend = (int) $maxAmountTosend;
		return $this;
	}

}
