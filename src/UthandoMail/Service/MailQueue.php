<?php
namespace UthandoMail\Service;

use UthandoCommon\Service\AbstractMapperService;

class MailQueue extends AbstractMapperService
{
    protected $serviceAlias = 'UthandoMailMailQueue';
    
    /**
     * @var \UthandoMail\Options\MailOptions
     */
    protected $options;

    /**
     * @return int
     */
    public function processQueue()
    {
        $options = $this->getOptions();
        $numberToSend = $options->getMaxAmountToSend();
        $emailsToSend = $this->getMapper()->getMailsInQueue($numberToSend);

        $numberSent = 0;
        
        /* @var $row \UthandoMail\Model\MailQueue */
        foreach ($emailsToSend as $row) {
            
            if ($row->getLayout()) {
                $options->setLayout($row->getLayout());
            }
            
            $message = $options->compose($row->getBody());
            
            $message->addTo($row->getRecipient())
                ->addFrom($row->getSender())
                ->setSubject($row->getSubject());
            
            $options->send($message, $row->getTransport());
            
            // delete the mail we just sent.
            $this->delete($row->getMailQueueId());
            $numberSent++;
        }
        
        return $numberSent;
    }
    
    /**
     * @return \UthandoMail\Options\MailOptions
     */
    public function getOptions()
    {
        if (!$this->options) {
            $sl = $this->getServiceLocator();
            $this->options = $sl->get('UthandoMail\Options\MailOptions');
        }
        
        return $this->options;
    }
}
