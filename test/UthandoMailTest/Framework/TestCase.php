<?php

namespace UthandoTwitterTest\Framework;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class TestCase extends AbstractHttpControllerTestCase
{
    protected function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../TestConfig.php.dist'
        );
        parent::setUp();
    }
}