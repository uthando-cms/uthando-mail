<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Event
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Event;

use UthandoCommon\Service\AbstractService;
use UthandoCommon\Service\ServiceException;
use UthandoCommon\Service\ServiceManager;
use UthandoMail\Service\Mail;
use UthandoMail\Service\MailQueueService;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class MailListener
 *
 * @package UthandoMail\Event
 */
class MailListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $listeners = [
            AbstractService::class,
            AbstractActionController::class,
        ];

        $this->listeners[] = $sharedEvents->attach($listeners, 'mail.send', [$this, 'sendMail']);
        $this->listeners[] = $sharedEvents->attach($listeners, 'mail.queue', [$this, 'queueMail']);
    }

    /**
     * @param Event $e
     */
    public function sendMail(Event $e)
    {
        $data = $e->getParams();
        /* @var ServiceManager $sl */
        $sl = $e->getTarget()->getServiceLocator();

        /* @var $sendMail Mail */
        $sendMail = $sl->get(Mail::class);

        if (isset($data['renderer']) && $sl->getServiceLocator()->has($data['renderer'])) {
            $sendMail->setView(
                $sl->get($data['renderer'])
            );
        }

        if (isset($data['layout'])) {
            $sendMail->setLayout($data['layout']);

            if (isset($data['layout_params'])) {
                $sendMail->getLayout()->setVariables($data['layout_params']);
            }
        }

        $subject = $data['subject'];
        $transport = $data['transport'];
        $body = $data['body'];

        $recipient = (isset($data['recipient'])) ? $data['recipient'] : $sendMail->getOption('AddressList')[$transport];

        if (is_array($recipient)) {
            $to = $sendMail->createAddress($recipient['address'], $recipient['name']);
        } else {
            $to = $recipient;
        }

        $sender = (isset($data['sender'])) ? $data['sender'] : $sendMail->getOption('AddressList')[$transport];

        if (is_array($sender)) {
            $from = $sendMail->createAddress($sender['address'], $sender['name']);
        } else {
            $from = $sender;
        }

        $message = $sendMail->compose($body)
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject);

        $sendMail->send($message, $transport);
    }

    /**
     * @param Event $e
     * @throws ServiceException
     */
    public function queueMail(Event $e)
    {
        /* @var ServiceManager $sl */
        $sl = $e->getTarget()->getServiceLocator();
        $data = $e->getParams();

        /* @var $mailQueue MailQueueService */
        $mailQueue = $sl->get(MailQueueService::class);
        $hydrator = $mailQueue->getHydrator();
        $model = $mailQueue->getModel();

        $model = $hydrator->hydrate($data, $model);

        $mailQueue->save($model);
    }
}
