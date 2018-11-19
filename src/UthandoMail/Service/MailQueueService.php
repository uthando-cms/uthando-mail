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
use UthandoMail\Hydrator\MailQueueHydrator;
use UthandoMail\Mapper\MailQueueMapper as MailQueueMapper;
use UthandoMail\Model\MailQueueModel as MailQueueModel;
use UthandoMail\Options\MailOptions;

/**
 * Class MailQueue
 *
 * @package UthandoMail\Service
 * @method MailQueueMapper getMapper($mapperClass = null, array $options = [])
 */
class MailQueueService extends AbstractMapperService
{
    protected $hydrator     = MailQueueHydrator::class;
    protected $mapper       = MailQueueMapper::class;
    protected $model        = MailQueueModel::class;
    
    /**
     * @var MailOptions
     */
    protected $options;

    /**
     * @var array
     */
    protected $failedMessages;

    /**
     * @return int
     */
    public function processQueue()
    {
        /* @var $sendMail Mail */
        $sendMail = $this->getService(Mail::class);
        /* @var $options MailOptions */
        $options = $this->getService(MailOptions::class);

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

            try {
                $sendMail->send($message, $transport);
                $numberSent++;
            } catch (\Exception $e) {
                $this->setFailedMessage($to);
            }
            
            // delete the mail we just sent.
            $this->delete($row->getMailQueueId());
        }
        
        return $numberSent;
    }

    /**
     * @return array
     */
    public function getFailedMessages()
    {
        return $this->failedMessages;
    }

    public function setFailedMessage($address)
    {
        $this->failedMessages[] = $address;
    }

    /**
     * @param array $failedMessages
     * @return $this
     */
    public function setFailedMessages($failedMessages)
    {
        $this->failedMessages = $failedMessages;
        return $this;
    }
}
