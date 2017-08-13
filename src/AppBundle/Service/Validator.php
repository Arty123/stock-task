<?php
/**
 * Created by PhpStorm.
 * User: a.abelyan
 * Date: 10.08.2017
 * Time: 18:57.
 */

namespace AppBundle\Service;

class Validator
{
    const MESSAGES = [
        'import_rules' => [
            'first_case' => 'price less than 5 and stock level less than 10',
            'second_case' => 'price more than 1000',
        ],
        'broken_data' => [
            'fail_count' => 'count of items not equals 6',
            'fail_price' => 'price contains unexpected symbol',
            'fail_stock' => 'stock level is not defined',
        ],
    ];

    /**
     * @var
     */
    private $data;

    /**
     * @var mixed
     */
    private $dataCode;

    /**
     * @var float
     */
    private $dataPrice;

    /**
     * @var int
     */
    private $dataStock;

    /**
     * @var int
     */
    private $dataDiscounted;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Validator constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Function init return true or false. It depends of.
     *
     * @param array $data
     *
     * @return bool
     */
    public function init(array $data)
    {
        // Valid csv row have to contain 6 fields
        if (isset($data) && $this->validateBrokenData($data)) {
            $this->data = $data;
            $this->dataCode = $data[0];
            $this->dataPrice = (float) $data[4];
            $this->dataStock = (int) $data[3];
            $this->dataDiscounted = $data[5];

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function validateImportRules()
    {
        if (($this->dataPrice <= 5 && $this->dataStock <= 10)) {
            $this->logger->failImportRulesLog(self::MESSAGES['import_rules']['first_case']);

            return false;
        } elseif (($this->dataPrice >= 1000)) {
            $this->logger->failImportRulesLog(self::MESSAGES['import_rules']['second_case']);

            return false;
        } elseif ($this->dataDiscounted == 'yes') {
            $this->logger->discountedItemsLog();
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function validateBrokenData(array $data)
    {
        if (count($data) != 6) {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_count']);

            return false;
        } elseif (strpos($data[4], '$') !== false) {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_price']);

            return false;
        } elseif ($data[3] == '') {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_stock']);

            return false;
        }

        return true;
    }
}
