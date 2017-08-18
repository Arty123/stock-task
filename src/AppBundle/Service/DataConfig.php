<?php

namespace AppBundle\Service;

/**
 * Class DataConfig
 * @package AppBundle\Service
 */
class DataConfig
{
    /**
     * @var int
     */
    private $code = 0;

    /**
     * @var int
     */
    private $name = 1;

    /**
     * @var int
     */
    private $description = 2;

    /**
     * @var int
     */
    private $stock = 3;

    /**
     * @var int
     */
    private $price = 4;

    /**
     * @var int
     */
    private $discounted = 5;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getDiscounted()
    {
        return $this->discounted;
    }
}