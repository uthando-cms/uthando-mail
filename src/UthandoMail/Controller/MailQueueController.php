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

use UthandoCommon\Controller\AbstractCrudController;
use UthandoMail\Service\MailQueueService;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class MailQueueController
 *
 * @package UthandoMail\Controller
 */
class MailQueueController extends AbstractCrudController
{
    protected $controllerSearchOverrides = ['sort' => 'mailQueueId'];
    protected $serviceName = MailQueueService::class;
    protected $route = 'admin/mail-queue';

    /**
     * @return \Zend\Http\Response
     */
    public function addAction()
    {
        return $this->redirect()->toRoute($this->route);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function editAction()
    {
        return $this->redirect()->toRoute($this->route);
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $del     = $request->getPost('submit', 'No');
        $ids     = $request->getPost('ids', []);

        if ($request->isPost() && $del == 'delete') {

            $result = $this->getService()->delete($ids);

            $this->flashMessenger()->addSuccessMessage(sprintf('%s rows were deleted form database.', $result));

        }

        return $this->redirect()->toRoute($this->getRoute('delete'), $this->params()->fromRoute());
    }
}
