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
use UthandoMail\Mapper\MailQueue as MailQueueMapper;
use UthandoMail\Model\MailQueue as MailQueueModel;
use UthandoMail\Options\MailOptions;

/**
 * Class MailQueue
 *
 * @package UthandoMail\Service
 * @method MailQueueMapper getMapper($mapperClass = null, array $options = [])
 */
class MailQueue extends AbstractMapperService
{
    protected $serviceAlias = 'UthandoMailQueue';
    
    /**
     * @var MailOptions
     */
    protected $options;

    /**
     * @return int
     */
    public function processQueue()
    {
        /* @var $sendMail Mail */
        $sendMail = $this->getService('UthandoMail\Service\Mail');
        /* @var $options MailOptions */
        $options = $this->getService('UthandoMail\Options\MailOptions');

        $numberToSend = $options->getMaxAmountToSend();
        $emailsToSend = $this->getMapper()->getMailsInQueue($numberToSend);

        $numberSent = 0;
        
        /* @var $row MailQueueModel */
        foreach ($emailsToSend as $row) {

            $body       = $row->getBody();
            $transport  = $row->getTransport();
            $recipient  = ($row->getRecipient()) ? $row->getRecipient() : $sendMail->getOption('AddressList')[$transport];
            $sender     = ($row->getSender()) ? $row->getSender() : $sendMail->getOption('AddressList')[$transport];

            if ($row->getRenderer() && $this->getServiceLocator()->getServiceLocator()->has($row->getRenderer())) {
                $renderer = $this->getServiceLocator()->getServiceLocator()->get($row->getRenderer());
                $sendMail->setView($renderer);
            }
            
            $sendMail->setLayout($row->getLayout());

            if (is_array($recipient)) {
                $to = $sendMail->createAddress($recipient['address'], $recipient['name']);
            } else {
                $to = $recipient;
            }

            if (is_array($sender)) {
                $from = $sendMail->createAddress($sender['address'], $sender['name']);
            } else {
                $from = $sender;
            }

            $message = $sendMail->compose($body)
                ->setTo($to)
                ->setFrom($from)
                ->setSubject($row->getSubject());

            $sendMail->send($message, $transport);
            
            // delete the mail we just sent.
            $this->delete($row->getMailQueueId());
            $numberSent++;
        }
        
        return $numberSent;
    }
}
