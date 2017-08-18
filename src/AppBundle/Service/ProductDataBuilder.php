<?php

namespace AppBundle\Service;

use AppBundle\Entity\ProductData;

class ProductDataBuilder
{
    /**
     * @var string
     */
    public $productName = '';

    /**
     * @var string
     */
    public $productDesc = '';

    /**
     * @var string
     */
    public $productCode = '';

    /**
     * @var null
     */
    public $discounted;

    /**
     * @var string
     */
    public $stockLevel = '';

    /**
     * @var float
     */
    public $price = 0.00;

    /**
     * @return ProductData
     */
    public function build()
    {
        return new ProductData($this);
    }
}