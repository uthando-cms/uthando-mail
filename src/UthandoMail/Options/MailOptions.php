<?php
namespace UthandoMail\Options;

use Zend\Stdlib\AbstractOptions;

class MailOptions extends AbstractOptions
{
    protected $mailTransport;
    protected $addressList;

	/**
	 * @return the $mailTransport
	 */
	public function getMailTransport()
	{
		return $this->mailTransport;
	}

	/**
	 * @param field_type $mailTransport
	 */
	public function setMailTransport($mailTransport)
	{
		$this->mailTransport = $mailTransport;
		return $this;
	}

	/**
	 * @return the $addressList
	 */
	public function getAddressList()
	{
		return $this->addressList;
	}

	/**
	 * @param field_type $addressList
	 */
	public function setAddressList($addressList)
	{
		$this->addressList = $addressList;
		return $this;
	}
}
