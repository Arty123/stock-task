<?php

namespace AppBundle\Service;

use AppBundle\Service\Converter as Converter;
use Doctrine\ORM\EntityManager as EntityManager;

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
     * @var ProductDataBuilder
     */
    private $builder;

    /**
     * DataManager constructor.
     * @param EntityManager $em
     * @param \AppBundle\Service\Converter $converter
     * @param DataConfig $dataConfig
     * @param ProductDataBuilder $builder
     */
    public function __construct(EntityManager $em, Converter $converter, DataConfig $dataConfig, ProductDataBuilder $builder)
    {
        // Define EntityManager
        $this->em = $em;
        // Define converter
        $this->converter = $converter;
        // Define data configuration
        $this->dataConfig = $dataConfig;
        // Define builder
        $this->builder = $builder;
    }

    /**
     * @param $testMode
     * @param array $data
     */
    public function manageData($testMode, array $data)
    {
        // Convert charset of any string in data array
        $this->converter->convertCharset($data);

        // Set item's properties
        $this->builder->productCode = $data[$this->dataConfig->getCode()];
        $this->builder->productName = $data[$this->dataConfig->getName()];
        $this->builder->productDesc = $data[$this->dataConfig->getDescription()];
        $this->builder->stockLevel = $data[$this->dataConfig->getStock()];
        $this->builder->price = $data[$this->dataConfig->getPrice()];

        // Check discounted field
        if ($data[$this->dataConfig->getDiscounted()] == 'yes') {
            $this->builder->discounted = new \DateTime('now');
        }

        $productData = $this->builder->build();

        // Insert or update item if testMode off
        if (!$testMode) {
            $this->em->persist($productData);
            $this->em->flush();
        }
    }
}