<?php
namespace UthandoMail\Service;

use UthandoCommon\Service\AbstractService;

class MailQueue extends AbstractService
{
    protected $mapperClass = 'UthandoMail\Mapper\MailQueue';
    protected $form;
    protected $inputFilter = 'UthandoMail\InputFilter\MailQueue';
    
    /**
     * @var \UthandoMail\Options\MailQueueOptions
     */
    protected $options;
    
    public function processQueue()
    {
        $numberToSend = $this->getOptions()->getMaxAmountToSend();
        $emailsToSend = $this->getMapper()->getMailsInQueue($numberToSend);

        $sendMail = $this->getServiceLocator()->get('UthandoMail\Service\Mail');
        $numberSent = 0;
        
        /* @var $row \UthandoMail\Model\MailQueue */
        foreach ($emailsToSend as $row) {
            
            if ($row->getLayout()) {
                $sendMail->setLayout($row->getLayout());
            }
            
            $message = $sendMail->compose($row->getBody());
            
            $message->addTo($row->getRecipient())
                ->addFrom($row->getSender())
                ->setSubject($row->getSubject());
            
            $sendMail->send($message, $row->getTransport());
            
            // delete the mail we just sent.
            $this->delete($row->getMailQueueId());
            $numberSent++;
        }
        
        return $numberSent;
    }
    
    /**
     * @return \UthandoMail\Options\MailQueueOptions
     */
    public function getOptions()
    {
        if (!$this->options) {
            $sl = $this->getServiceLocator();
            $this->options = $sl->get('UthandoMail\Options\MailQueueOptions');
        }
        
        return $this->options;
    }
}
