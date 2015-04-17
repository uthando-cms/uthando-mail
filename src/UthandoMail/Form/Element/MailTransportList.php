<?php
namespace UthandoMail\Form\Element;

use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class MailTransportList extends Select implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    public function init()
    {
        /* @var $options \UthandoMail\Options\MailOptions */
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
