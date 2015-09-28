<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Form\Element
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Form\Element;

use UthandoMail\Options\MailOptions;
use Zend\Form\Element\Select;
use Zend\Form\FormElementManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class MailTransportList
 *
 * @package UthandoMail\Form\Element
 * @method FormElementManager getServiceLocator()
 */
class MailTransportList extends Select implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Set up value options
     */
    public function init()
    {
        /* @var $options MailOptions */
        $options = $this->getServiceLocator()
            ->getServiceLocator()
            ->get('UthandoMail\Options\MailOptions');

        $emailAddresses = $options->getAddressList();

        $addressList = [];

        foreach ($emailAddresses as $transport => $address) {

            $addressList[] = [
                'label' => $address['name'] . ' <' . $address['address'] . '>',
                'value' => $transport,
            ];
        }

        $this->setValueOptions($addressList);
    }
}
