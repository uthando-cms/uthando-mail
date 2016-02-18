<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\View\Helper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\View\Helper;

use UthandoCommon\View\AbstractViewHelper;
use UthandoMail\Model\MailQueue;
use UthandoMail\Options\MailOptions;
use Zend\Mail\Address;
use Zend\View\Exception\InvalidArgumentException;

/**
 * Class MailAddress
 *
 * @package UthandoMail\View\Helper
 */
class MailAddress extends AbstractViewHelper
{
    /**
     * @var MailOptions
     */
    protected $mailOptions;

    /**
     * @param MailQueue $row
     * @param string $type
     * @return string
     */
    public function __invoke(MailQueue $row, $type)
    {
        if (!$row->has($type)) {
            throw new InvalidArgumentException('wrong type given in class ' . __CLASS__);
        }

        $method = 'get' . ucwords($type);

        $address = $row->$method();

        if (null === $address) {
            $address = $this->getMailOptions()
                ->getAddressList()[$row->getTransport()];

            if (!$address instanceof Address) {
                $address = new Address(
                    $address['address'],
                    $address['name']
                );
            }
        }

        if ($address instanceof Address) {
            $address = $address->toString();
        }

        return $address;
    }

    /**
     * @return MailOptions
     */
    public function getMailOptions()
    {
        if (!$this->mailOptions instanceof MailOptions) {
            $options = $this->getServiceLocator()
                ->getServiceLocator()
                ->get('UthandoMail\Options\MailOptions');
            $this->mailOptions = $options;
        }

        return $this->mailOptions;
    }
}
