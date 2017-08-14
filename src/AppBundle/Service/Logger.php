<?php

namespace AppBundle\Service;

use AppBundle\ServiceInterface\LoggerInterface;

/**
 * Class Logger
 * @package AppBundle\Service
 */
class Logger implements LoggerInterface
{
    /**
     * @var array
     */
    public static $logger = [
        'total' => 0,
        'fail' => [
            'fail_total' => 0,
            'fail_import_rules' => [],
            'fail_broken_data' => [],
        ],
        'discounted_items' => [],
        'success' => 0,
    ];

    /**
     * @var mixed
     */
    private $dataCode;

    /**
     * @param array $data
     */
    public function init(array $data)
    {
        if (isset($data)) {
            $this->dataCode = $data[0];
            $this->increaseTotal();
        }
    }

    /**
     * Increase total items.
     */
    public function increaseTotal()
    {
        ++self::$logger['total'];
        self::$logger['success'] = self::$logger['total'] - self::$logger['fail']['fail_total'];
    }

    /**
     * @param $message
     */
    public function failImportRulesLog($message)
    {
        ++self::$logger['fail']['fail_total'];
        self::$logger['success'] = self::$logger['total'] - self::$logger['fail']['fail_total'];
        array_push(self::$logger['fail']['fail_import_rules'], $this->dataCode.' '.$message);
    }

    /**
     * @param $message
     */
    public function failBrokenDataLog($message)
    {
        ++self::$logger['fail']['fail_total'];
        self::$logger['success'] = self::$logger['total'] - self::$logger['fail']['fail_total'];
        array_push(self::$logger['fail']['fail_broken_data'], $this->dataCode.' '.$message);
    }

    /**
     * Log discounted items.
     */
    public function discountedItemsLog()
    {
        array_push(self::$logger['discounted_items'], $this->dataCode);
    }
}
