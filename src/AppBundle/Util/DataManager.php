<?php

namespace AppBundle\Util;

use AppBundle\Service\Converter as Converter;
use Doctrine\ORM\EntityManager as EntityManager;
use AppBundle\Entity\ProductData;

/**
 * Class DataManager
 * @package AppBundle\Util
 */
class DataManager
{
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
     * @param EntityManager $em
     * @param Converter $converter
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
        $productData->setStrProductCode($data[0]);
        $productData->setStrProductName($data[1]);
        $productData->setStrProductDesc($data[2]);
        $productData->setIntStockLevel($data[3]);
        $productData->setDecPrice($data[4]);

        // Check discounted field
        if ($data[5] == 'yes') {
            $productData->setDtmDiscounted(new \DateTime('now'));
        }

        // Insert or update item if testMode off
        if (!$testMode) {
            $this->em->persist($productData);
            $this->em->flush();
        }
    }
}