<?php
/**
 * Created by PhpStorm.
 * User: a.abelyan
 * Date: 10.08.2017
 * Time: 18:57
 */

namespace AppBundle\Service;


class Validator
{
    /**
     * @var
     */
    private $data;

    /**
     * @var mixed
     */
    private $dataName;

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
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $data
     */
    public function init(array $data)
    {
        if (isset($data) && (count($data) == 6)) {
            $this->data = $data;
            $this->dataName = $data[0];
            $this->dataPrice = (float) $data[4];
            $this->dataStock = (integer) $data[3];
            $this->dataDiscounted = $data[5];

            $this->logger->init($data);
            $this->logger->increaseTotal();

            return true;
        } else {

            $this->logger->failBrokenDataLog();
            $this->logger->increaseTotal();

            return false;
        }
    }

    /**
     * @return bool
     */
    public function validateImportRules()
    {
        if (($this->dataPrice < 5 && $this->dataStock < 10) || ($this->dataPrice > 1000)) {
            $this->logger->failImportRulesLog();

            return false;

        } elseif ($this->dataDiscounted == 'yes') {
            $this->logger->discountedItemsLog();

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function validateData()
    {
        // Valid csv row have to contain 6 fields
        if (count($this->data) != 6) {

            $this->logger->failBrokenDataLog();

            return false;
        }

        return true;
    }
}