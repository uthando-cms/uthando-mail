<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoMail\Mapper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoMail\Mapper;

use UthandoCommon\Mapper\AbstractDbMapper;

/**
 * Class MailQueue
 *
 * @package UthandoMail\Mapper
 */
class MailQueueMapper extends AbstractDbMapper
{
    protected $table = 'mailQueue';
    protected $primary = 'mailQueueId';

    /**
     * @param int $limit
     * @return \Zend\Db\ResultSet\HydratingResultSet|\Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator
     */
    public function getMailsInQueue($limit)
    {
        $select = $this->getSelect();
        $select = $this->setLimit($select, $limit, 0);
        $select = $this->setSortOrder($select, 'priority');

        return $this->fetchResult($select);
    }
}
