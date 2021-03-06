<?php

namespace AppBundle\Test\Service;

use AppBundle\Service\Logger;
use AppBundle\Service\DataConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class LoggerTest.
 */
class LoggerTest extends TestCase
{
    private $logger;

    private $dataConfig;

    public function setUp()
    {
        $this->dataConfig = new DataConfig();
        $this->logger = new Logger($this->dataConfig);
    }

    public function tearDown()
    {
    }

    public function testIncreaseTotal()
    {
        $this->logger->increaseTotal();
        $this->assertEquals(Logger::$logger['total'], 1);
    }

    public function testFailImportRulesLog()
    {
        $this->logger->init(['P001']);
        $this->logger->failImportRulesLog('message');
        $this->assertEquals(Logger::$logger['total'], 2);
        $this->assertEquals(Logger::$logger['fail']['fail_total'], 1);
        $this->assertEquals(Logger::$logger['success'], 1);
        $this->assertEquals(Logger::$logger['fail']['fail_import_rules'][0], 'P001 message');
    }

    public function testFailBrokenDataLog()
    {
        $this->logger->init(['P002']);
        $this->logger->failBrokenDataLog('message 2');
        $this->assertEquals(Logger::$logger['total'], 3);
        $this->assertEquals(Logger::$logger['fail']['fail_total'], 2);
        $this->assertEquals(Logger::$logger['success'], 1);
        $this->assertEquals(Logger::$logger['fail']['fail_broken_data'][0], 'P002 message 2');
    }
}
