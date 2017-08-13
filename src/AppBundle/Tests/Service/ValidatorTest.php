<?php

namespace AppBundle\Test\Service;

use AppBundle\Service\Validator;
use AppBundle\Service\Logger;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    private $validator;

    private $logger;

    public function setUp()
    {
        $this->logger = new Logger();
        $this->validator = new Validator($this->logger);
    }

    public function tearDown()
    {
    }

    public function testInitValidator()
    {
        $data = ['P001', 'Name', 'Description', '5', '5.55', 'yes'];
        $dataLessCount = ['P001', 'Name', 'Description', '5', '5.55'];
        $dataMoreCount = ['P001', 'Name', 'Description', '5', '5.55', 'yes', ''];
        $dataWongPrice = ['P001', 'Name', 'Description', '5', '$5.55', 'yes'];
        $dataNoneStock = ['P001', 'Name', 'Description', '', '$5.55', 'yes'];

        $result = $this->validator->init($data);
        $this->assertEquals($result, true);

        $result = $this->validator->init($dataLessCount);
        $this->assertEquals($result, false);

        $result = $this->validator->init($dataMoreCount);
        $this->assertEquals($result, false);

        $result = $this->validator->init($dataWongPrice);
        $this->assertEquals($result, false);

        $result = $this->validator->init($dataNoneStock);
        $this->assertEquals($result, false);
    }

    public function testValidateImportRules()
    {
        $data = ['P001', 'Name', 'Description', '5', '5.55', 'yes'];
        $dataPriceAndStockLessCase1 = ['P001', 'Name', 'Description', '9', '4.99', ''];
        $dataPriceAndStockLessCase2 = ['P001', 'Name', 'Description', '9', '5', ''];
        $dataPriceAndStockMore = ['P001', 'Name', 'Description', '9', '1000.01', ''];
        $dataDiscounted = ['P001', 'Name', 'Description', '11', '5.01', 'yes'];

        $this->validator->init($data);
        $result = $this->validator->validateImportRules();
        $this->assertEquals($result, true);

        $this->validator->init($dataPriceAndStockLessCase1);
        $result = $this->validator->validateImportRules();
        $this->assertEquals($result, false);

        $this->validator->init($dataPriceAndStockLessCase2);
        $result = $this->validator->validateImportRules();
        $this->assertEquals($result, false);

        $this->validator->init($dataPriceAndStockMore);
        $result = $this->validator->validateImportRules();
        $this->assertEquals($result, false);

        $this->validator->init($dataDiscounted);
        $result = $this->validator->validateImportRules();
        $this->assertEquals($result, true);
    }
}