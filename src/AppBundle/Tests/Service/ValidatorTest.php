<?php

namespace AppBundle\Test\Service;

use AppBundle\Service\Validator;
use AppBundle\Service\Logger;
use AppBundle\Service\DataConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest.
 */
class ValidatorTest extends TestCase
{
    private $validator;

    private $logger;

    private $dataConfig;

    public function setUp()
    {
        $this->dataConfig = new DataConfig();

        $this->logger = new Logger($this->dataConfig);
        $this->validator = new Validator($this->logger, $this->dataConfig);
    }

    public function tearDown()
    {
    }

    public function testInitValidator()
    {
        $data = ['P001', 'Name', 'Description', '5', '5.55', 'yes'];
        $dataLessCount = ['P002', 'Name', 'Description', '5', '5.55'];
        $dataMoreCount = ['P003', 'Name', 'Description', '5', '5.55', 'yes', ''];
        $dataWongPrice = ['P004', 'Name', 'Description', '5', '$5.55', 'yes'];
        $dataNoneStock = ['P005', 'Name', 'Description', '', '$5.55', 'yes'];

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
        $data = ['P0011', 'Name', 'Description', '5', '5.55', 'yes'];
        $dataPriceAndStockLessCase1 = ['P0022', 'Name', 'Description', '9', '4.99', ''];
        $dataPriceAndStockLessCase2 = ['P0033', 'Name', 'Description', '9', '5', ''];
        $dataPriceAndStockMore = ['P0044', 'Name', 'Description', '9', '1000.01', ''];
        $dataDiscounted = ['P0055', 'Name', 'Description', '11', '5.01', 'yes'];
        $dataNotUniqueCode = ['P0055', 'Name', 'Description', '11', '5.01', 'yes'];

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

        $result = $this->validator->init($dataNotUniqueCode);
        $this->assertEquals($result, false);
    }
}
