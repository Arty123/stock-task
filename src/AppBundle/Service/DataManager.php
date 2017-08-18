<?php

namespace AppBundle\Service;

use AppBundle\Service\Converter as Converter;
use Doctrine\ORM\EntityManager as EntityManager;
use AppBundle\Entity\ProductData;

/**
 * Class DataManager.
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
     * @var DataConfig
     */
    private $dataConfig;

    /**
     * DataManager constructor.
     * @param EntityManager $em
     * @param Converter $converter
     * @param DataConfig $dataConfig
     */
    public function __construct(EntityManager $em, Converter $converter, DataConfig $dataConfig)
    {
        // Define EntityManager
        $this->em = $em;
        // Define converter
        $this->converter = $converter;
        // Define data configuration
        $this->dataConfig = $dataConfig;
    }

    /**
     * @param $testMode
     * @param array $data
     */
    public function manageData($testMode, array $data)
    {
        // Convert charset of any string in data array
        $this->converter->convertCharset($data);

        $productData = new ProductData();

        // Set item's properties
        $productData->setProductCode($data[$this->dataConfig->getCode()]);
        $productData->setProductName($data[$this->dataConfig->getName()]);
        $productData->setProductDesc($data[$this->dataConfig->getDescription()]);
        $productData->setStockLevel($data[$this->dataConfig->getStock()]);
        $productData->setPrice($data[$this->dataConfig->getPrice()]);

        // Check discounted field
        if ($data[$this->dataConfig->getDiscounted()] == 'yes') {
            $productData->setDiscounted(new \DateTime('now'));
        }

        // Insert or update item if testMode off
        if (!$testMode) {
            $this->em->persist($productData);
            $this->em->flush();
        }
    }
}