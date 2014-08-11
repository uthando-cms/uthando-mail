<?php
namespace UthandoMail\Options;

use Zend\Stdlib\AbstractOptions;

class MailOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $mailTransport;
    
    /**
     * @var array
     */
    protected $addressList;
    
    /**
     * @var bool
     */
    protected $generateAlternativeBody;
    
    /**
     * @var string
     */
    protected $layout;
    
    /**
     * @var string
     */
    protected $charset;

	/**
	 * @return array $mailTransport
	 */
	public function getMailTransport()
	{
		return $this->mailTransport;
	}

	/**
	 * @param array $mailTransport
	 */
	public function setMailTransport($mailTransport)
	{
		$this->mailTransport = $mailTransport;
		return $this;
	}

	/**
	 * @return array $addressList
	 */
	public function getAddressList()
	{
		return $this->addressList;
	}

	/**
	 * @param array $addressList
	 */
	public function setAddressList($addressList)
	{
		$this->addressList = $addressList;
		return $this;
	}
	
	/**
	 * @return bool $generateAlternativeBody
	 */
	public function getGenerateAlternativeBody()
	{
		return $this->generateAlternativeBody;
	}

	/**
	 * @param boolean $generateAlternativeBody
	 */
	public function setGenerateAlternativeBody($generateAlternativeBody)
	{
		$this->generateAlternativeBody = $generateAlternativeBody;
		return $this;
	}
	
	/**
	 * @return the $layout
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
	 * @return the $charset
	 */
	public function getCharset()
	{
		return $this->charset;
	}

	/**
	 * @param string $charset
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;
		return $this;
	}


}
