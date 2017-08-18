<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Service\ProductDataBuilder;

/**
 * ProductData
 *
 * @ORM\Table(name="product_data")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductDataRepository")
 */
class ProductData
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=50)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="product_desc", type="string", length=255)
     */
    private $productDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="product_code", type="string", length=10, unique=true)
     */
    private $productCode;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="added", type="datetime", nullable=true)
     */
    private $added;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="discounted", type="datetime", nullable=true)
     */
    private $discounted;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_level", type="integer", nullable=true)
     */
    private $stockLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * ProductData constructor.
     * @param ProductDataBuilder $builder
     */
    public function __construct(ProductDataBuilder $builder)
    {
        $this->productCode = $builder->productCode;
        $this->productName = $builder->productName;
        $this->productDesc = $builder->productDesc;
        $this->price = $builder->price;
        $this->stockLevel = $builder->stockLevel;
        $this->discounted = $builder->discounted;
    }
}
