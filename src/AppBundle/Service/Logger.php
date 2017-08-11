<?php
/**
 * Created by PhpStorm.
 * User: a.abelyan
 * Date: 11.08.2017
 * Time: 11:23
 */

namespace AppBundle\Service;


class Logger
{
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
     * @var array
     */
    public static $logger = [
        'total' => 0,
        'fail'  => [
            'fail_total' => 0,
            'fail_import_rules' => [],
            'fail_broken_data'  => [],
        ],
        'discounted_items' => []
    ];

    /**
     * @param array $data
     */
    public function init(array $data)
    {
        if (isset($data) && (count($data) == 6)) {
            $this->dataName = $data[0];
            $this->dataPrice = (float) $data[4];
            $this->dataStock = (integer) $data[3];
            $this->dataDiscounted = $data[5];
        }
    }

    /**
     * Increase total items.
     */
    public function increaseTotal()
    {
        self::$logger['total']++;
    }

    /**
     * Log fail rules item.
     */
    public function failImportRulesLog()
    {
        self::$logger['fail']['fail_total']++;
        array_push(self::$logger['fail']['fail_import_rules'], $this->dataName);
    }

    /**
     * Log broken data item.
     */
    public function failBrokenDataLog()
    {
        self::$logger['fail']['fail_total']++;
        array_push(self::$logger['fail']['fail_broken_data'], $this->dataName);
    }

    /**
     * Log discounted items.
     */
    public function discountedItemsLog()
    {
        array_push(self::$logger['discounted_items'], $this->dataName);
    }
}