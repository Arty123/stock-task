<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\Column(name="strProductName", type="string", length=50)
     */
    private $strProductName;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     */
    private $strProductDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, unique=true)
     */
    private $strProductCode;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmDiscounted", type="datetime", nullable=true)
     */
    private $dtmDiscounted;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="stmTimestamp", type="datetime")
     */
    private $stmTimestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="intStockLevel", type="integer", nullable=true)
     */
    private $intStockLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="decPrice", type="decimal", precision=10, scale=2)
*/
    private $decPrice;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set strProductName
     *
     * @param string $strProductName
     * @return ProductData
     */
    public function setStrProductName($strProductName)
    {
        $this->strProductName = $strProductName;

        return $this;
    }

    /**
     * Get strProductName
     *
     * @return string 
     */
    public function getStrProductName()
    {
        return $this->strProductName;
    }

    /**
     * Set strProductDesc
     *
     * @param string $strProductDesc
     * @return ProductData
     */
    public function setStrProductDesc($strProductDesc)
    {
        $this->strProductDesc = $strProductDesc;

        return $this;
    }

    /**
     * Get strProductDesc
     *
     * @return string 
     */
    public function getStrProductDesc()
    {
        return $this->strProductDesc;
    }

    /**
     * Set strProductCode
     *
     * @param string $strProductCode
     * @return ProductData
     */
    public function setStrProductCode($strProductCode)
    {
        $this->strProductCode = $strProductCode;

        return $this;
    }

    /**
     * Get strProductCode
     *
     * @return string 
     */
    public function getStrProductCode()
    {
        return $this->strProductCode;
    }

    /**
     * Set dtmAdded
     *
     * @param \DateTime $dtmAdded
     * @return ProductData
     */
    public function setDtmAdded($dtmAdded)
    {
        $this->dtmAdded = $dtmAdded;

        return $this;
    }

    /**
     * Get dtmAdded
     *
     * @return \DateTime 
     */
    public function getDtmAdded()
    {
        return $this->dtmAdded;
    }

    /**
     * Set dtmDiscounted
     *
     * @param \DateTime $dtmDiscounted
     * @return ProductData
     */
    public function setDtmDiscounted($dtmDiscounted)
    {
        $this->dtmDiscounted = $dtmDiscounted;

        return $this;
    }

    /**
     * Get dtmDiscounted
     *
     * @return \DateTime 
     */
    public function getDtmDiscounted()
    {
        return $this->dtmDiscounted;
    }

    /**
     * Set stmTimestamp
     *
     * @param \DateTime $stmTimestamp
     * @return ProductData
     */
    public function setStmTimestamp($stmTimestamp)
    {
        $this->stmTimestamp = $stmTimestamp;

        return $this;
    }

    /**
     * Get stmTimestamp
     *
     * @return \DateTime 
     */
    public function getStmTimestamp()
    {
        return $this->stmTimestamp;
    }

    /**
     * Set intStockLevel
     *
     * @param integer $intStockLevel
     * @return ProductData
     */
    public function setIntStockLevel($intStockLevel)
    {
        $this->intStockLevel = $intStockLevel;

        return $this;
    }

    /**
     * Get intStockLevel
     *
     * @return integer 
     */
    public function getIntStockLevel()
    {
        return $this->intStockLevel;
    }

    /**
     * Set decPrice
     *
     * @param string $decPrice
     * @return ProductData
     */
    public function setDecPrice($decPrice)
    {
        $this->decPrice = $decPrice;

        return $this;
    }

    /**
     * Get decPrice
     *
     * @return string 
     */
    public function getDecPrice()
    {
        return $this->decPrice;
    }
}
