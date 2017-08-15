<?php

namespace AppBundle\Util;

use AppBundle\Service\Converter as Converter;
use Doctrine\ORM\EntityManager as EntityManager;
use AppBundle\Entity\ProductData;

/**
 * Class DataManager.
 */
class DataManager
{
    const CODE = 0;

    const NAME = 1;

    const DESCRIPTION = 2;

    const STOCK = 3;

    const PRICE = 4;

    const DISCOUNTED = 5;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * DataManager constructor.
     *
     * @param EntityManager $em
     * @param Converter     $converter
     */
    public function __construct(EntityManager $em, Converter $converter)
    {
        // Define EntityManager
        $this->em = $em;
        // Define converter
        $this->converter = $converter;
    }

    /**
     * @param $testMode
     * @param array $data
     */
    public function manageData($testMode, array $data)
    {
        // Convert charset of any string in data array
        $this->converter->convertCharset($data);

        // Find existing item in database
        $productData = $this->em->getRepository('AppBundle:ProductData')
            ->findOneBy([
                'strProductCode' => $data[0],
            ]);

        // If item doesn't exist, create it
        if (!$productData) {
            $productData = new ProductData();
        }

        // Set item's properties
        $productData->setStrProductCode($data[self::CODE]);
        $productData->setStrProductName($data[self::NAME]);
        $productData->setStrProductDesc($data[self::DESCRIPTION]);
        $productData->setIntStockLevel($data[self::STOCK]);
        $productData->setDecPrice($data[self::PRICE]);

        // Check discounted field
        if ($data[self::DISCOUNTED] == 'yes') {
            $productData->setDtmDiscounted(new \DateTime('now'));
        }

        // Insert or update item if testMode off
        if (!$testMode) {
            $this->em->persist($productData);
            $this->em->flush();
        }
    }
}