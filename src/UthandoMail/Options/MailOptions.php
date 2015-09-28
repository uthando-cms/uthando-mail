<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Options
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class MailOptions
 *
 * @package UthandoMail\Options
 */
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
     * @var int
     */
    protected $maxAmountToSend;

    /**
     * @return array
     */
    public function getMailTransport()
    {
        return $this->mailTransport;
    }

    /**
     * @param $mailTransport
     * @return $this
     */
    public function setMailTransport($mailTransport)
    {
        $this->mailTransport = $mailTransport;
        return $this;
    }

    /**
     * @return array
     */
    public function getAddressList()
    {
        return $this->addressList;
    }

    /**
     * @param $addressList
     * @return $this
     */
    public function setAddressList($addressList)
    {
        $this->addressList = $addressList;
        return $this;
    }

    /**
     * @return bool
     */
    public function getGenerateAlternativeBody()
    {
        return $this->generateAlternativeBody;
    }

    /**
     * @param $generateAlternativeBody
     * @return $this
     */
    public function setGenerateAlternativeBody($generateAlternativeBody)
    {
        $this->generateAlternativeBody = $generateAlternativeBody;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param $charset
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAmountToSend()
    {
        return $this->maxAmountToSend;
    }

    /**
     * @param $maxAmountToSend
     * @return $this
     */
    public function setMaxAmountToSend($maxAmountToSend)
    {
        $this->maxAmountToSend = (int)$maxAmountToSend;
        return $this;
    }
}
