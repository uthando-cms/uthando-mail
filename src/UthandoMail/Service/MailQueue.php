<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Service;

use UthandoCommon\Service\AbstractMapperService;

/**
 * Class MailQueue
 *
 * @package UthandoMail\Service
 * @method \UthandoMail\Mapper\MailQueue getMapper($mapperClass = null, array $options = [])
 */
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
        /* @var $sendMail \UthandoMail\Service\Mail */
        $sendMail = $this->getService('UthandoMail\Service\Mail');
        /* @var $options \UthandoMail\Options\MailOptions */
        $options = $this->getService('UthandoMail\Options\MailOptions');

        $numberToSend = $options->getMaxAmountToSend();
        $emailsToSend = $this->getMapper()->getMailsInQueue($numberToSend);

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
}
