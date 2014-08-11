<?php
namespace UthandoMail\Mapper;

use UthandoCommon\Mapper\AbstractMapper;

class MailQueue extends AbstractMapper
{
    protected $table = 'mailQueue';
    protected $primary = 'mailQueueId';
    protected $model= 'UthandoMail\Model\MailQueue';
    protected $hydrator = 'UthandoMail\Hydrator\MailQueue';
    
    public function getMailsInQueue($limit)
    {
        $select = $this->getSelect();
        $select = $this->setLimit($select, $limit, 0);
        $select = $this->setSortOrder($select, 'priority');
        
        return $this->fetchResult($select);
    }
}
