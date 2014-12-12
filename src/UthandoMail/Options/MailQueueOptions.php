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
     * @return int
     */
	public function getMaxAmountToSend()
	{
		return $this->maxAmountToSend;
	}

    /**
     * @param $maxAmountTosend
     * @return $this
     */
	public function setMaxAmountTosend($maxAmountTosend)
	{
		$this->maxAmountToSend = (int) $maxAmountTosend;
		return $this;
	}

}
