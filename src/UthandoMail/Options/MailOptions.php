<?php
namespace UthandoMail\Options;

use Zend\Stdlib\AbstractOptions;

class MailOptions extends AbstractOptions
{
    protected $mailMessageClass;
    protected $mailTransport;
    protected $addressList;
    
	/**
	 * @return the $mailMessageClass
	 */
	public function getMailMessageClass()
	{
		return $this->mailMessageClass;
	}

	/**
	 * @param field_type $mailMessageClass
	 */
	public function setMailMessageClass($mailMessageClass)
	{
		$this->mailMessageClass = $mailMessageClass;
		return $this;
	}

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
