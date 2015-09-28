<?php

namespace UthandoMailTest\Framework;

use UthandoMailTest\Bootstrap;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
    }
}
