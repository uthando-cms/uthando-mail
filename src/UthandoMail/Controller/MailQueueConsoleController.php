<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Controller;

use UthandoCommon\Service\ServiceManager;
use UthandoMail\Service\MailQueueService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

/**
 * Class MailQueueConsoleController
 *
 * @package UthandoMail\Controller
 */
class MailQueueConsoleController extends AbstractActionController
{
    /**
     * @return string
     */
    public function sendAction()
    {
        $request = $this->getRequest();

        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $sl = $this->getServiceLocator()->get(ServiceManager::class);

        $mailQueueService = $sl->get(MailQueueService::class);

        $mailsSent = $mailQueueService->processQueue();

        return "No of mails sent: " . $mailsSent . "\r\n";
    }
}
