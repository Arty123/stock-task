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
     * @var array
     */
    public static $logger = [
        'total' => 0,
        'fail'  => [
            'fail_total' => 0,
            'fail_import_rules' => [],
            'fail_broken_data'  => [],
        ],
        'discounted_items' => [],
        'success' => 0
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
        }
    }

    /**
     * Increase total items.
     */
    public function increaseTotal()
    {
        self::$logger['total']++;
        self::$logger['success'] = self::$logger['total'] - self::$logger['fail']['fail_total'];
    }

    /**
     * Log fail rules item.
     */
    public function failImportRulesLog($message)
    {
        self::$logger['fail']['fail_total']++;
        self::$logger['success'] = self::$logger['total'] - self::$logger['fail']['fail_total'];
        array_push(self::$logger['fail']['fail_import_rules'], $this->dataCode.' '.$message);
    }

    /**
     * Log broken data item.
     */
    public function failBrokenDataLog($message)
    {
        self::$logger['fail']['fail_total']++;
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