<?php

namespace AppBundle\Test\Service;

use AppBundle\Service\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    private $converter;

    public function setUp()
    {
        $this->converter = new Converter();
    }

    public function tearDown()
    {
    }

    public function testConvertCharset()
    {
        $data = ['Â'];
        $this->assertEquals(mb_detect_encoding($data[0], 'auto'), 'UTF-8');
        $this->converter->convertCharset($data);
        $this->assertEquals(mb_detect_encoding($data[0], 'auto'), 'ASCII');
    }
}