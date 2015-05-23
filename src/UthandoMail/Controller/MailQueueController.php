<?php
namespace UthandoMail\Controller;

use UthandoCommon\Controller\AbstractCrudController;

class MailQueueController extends AbstractCrudController
{
    protected $controllerSearchOverrides = ['sort' => 'mailQueueId'];
    protected $serviceName = 'UthandoMail\Service\MailQueue';
    protected $route = 'admin/mail-queue';
    
    public function addAction()
    {
        return $this->redirect($this->route);
    }
    
    public function editAction()
    {
        return $this->redirect($this->route);
    }
}
