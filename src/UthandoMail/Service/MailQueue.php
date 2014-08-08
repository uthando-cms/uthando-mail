<?php
namespace UthandoMail\Service;

use UthandoCommon\Service\AbstractService;

class MailQueue extends AbstractService
{
    protected $mapperClass = 'UthandoMail\Mapper\MailQueue';
    protected $form;
    protected $inputFilter = 'UthandoMail\InputFilter\MailQueue';
}
