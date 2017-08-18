<?php

namespace AppBundle\Service;

use AppBundle\Service\Contracts\ValidatorInterface;

/**
 * Class Validator.
 */
class Validator implements ValidatorInterface
{
    const MESSAGES = [
        'import_rules' => [
            'first_case'    => 'price less than 5 and stock level less than 10',
            'second_case'   => 'price more than 1000',
        ],
        'broken_data' => [
            'fail_count'    => 'count of items not equals 6',
            'fail_unique'   => 'not unique productCode',
            'fail_price'    => 'price contains unexpected symbol',
            'fail_stock'    => 'stock level is not defined',
        ],
    ];

    /**
     * @var DataConfig
     */
    private $dataConfig;

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
     * @var array
     */
    private static $passedItems = [];

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Validator constructor.
     * @param Logger $logger
     * @param DataConfig $dataConfig
     */
    public function __construct(Logger $logger, DataConfig $dataConfig)
    {
        // Define logger
        $this->logger = $logger;
        // Define Data Config
        $this->dataConfig = $dataConfig;
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
            $this->dataCode = $data[$this->dataConfig->getCode()];
            $this->dataPrice = (float) $data[$this->dataConfig->getPrice()];
            $this->dataStock = (int) $data[$this->dataConfig->getStock()];
            $this->dataDiscounted = $data[$this->dataConfig->getDiscounted()];

            // Set item as passed from validator
            $this->setPassedItems($this->dataCode);

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function validateImportRules()
    {
        // First case
        if (($this->dataPrice <= 5 && $this->dataStock <= 10)) {
            $this->logger->failImportRulesLog(self::MESSAGES['import_rules']['first_case']);

            return false;
            // Second Case
        } elseif (($this->dataPrice >= 1000)) {
            $this->logger->failImportRulesLog(self::MESSAGES['import_rules']['second_case']);

            return false;
            // Discounted items check
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
        // This method use access for data items by indexes,
        // because it execute in $this->init() method,
        // before setting up current object properties

        // Validate count of elements
        if (count($data) != 6) {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_count']);

            return false;
        // Validate unique productCode
        } elseif ($this->checkUniquePassedItem($data[$this->dataConfig->getCode()])) {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_unique']);

            return false;
        // Validate price
        } elseif (filter_var($data[$this->dataConfig->getPrice()], FILTER_VALIDATE_FLOAT) === false) {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_price']);

            return false;
            // Validate stock level
        } elseif (filter_var($data[$this->dataConfig->getStock()], FILTER_VALIDATE_INT) === false) {
            $this->logger->failBrokenDataLog(self::MESSAGES['broken_data']['fail_stock']);

            return false;
        }

        return true;
    }

    /**
     * @param $productCode
     */
    private function setPassedItems($productCode)
    {
        array_push(self::$passedItems, $productCode);
    }

    /**
     * @return array
     */
    public function getPassedItems()
    {
        return self::$passedItems;
    }

    /**
     * @param $productCode
     * @return bool
     */
    private function checkUniquePassedItem($productCode)
    {
        if (in_array($productCode, self::$passedItems)) {
            return true;
        }

        return false;
    }
}
