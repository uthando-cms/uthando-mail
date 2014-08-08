<?php
namespace UthandoMail\Mapper;

use UthandoCommon\Mapper\AbstractMapper;

class MailQueue extends AbstractMapper
{
    protected $table = 'mailQueue';
    protected $primary = 'mailQueueId';
    protected $model= 'UthandoMail\Model\MailQueue';
    protected $hydrator = 'UthandoMail\Hydrator\MailQueue';
}
