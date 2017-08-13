<?php

namespace AppBundle\Test\Service;

use AppBundle\Service\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest
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
        $data = '$5.55';
        $this->converter->converCharset();
        $this->assertEquals($data, '5.55');
    }
}