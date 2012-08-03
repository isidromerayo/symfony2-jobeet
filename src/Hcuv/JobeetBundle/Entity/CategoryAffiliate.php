<?php

namespace Hcuv\JobeetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hcuv\JobeetBundle\Entity\CategoryAffiliate
 */
class CategoryAffiliate
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var Hcuv\JobeetBundle\Entity\Category
     */
    private $category;

    /**
     * @var Hcuv\JobeetBundle\Entity\Affiliate
     */
    private $affiliate;


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
     * Set category
     *
     * @param Hcuv\JobeetBundle\Entity\Category $category
     * @return CategoryAffiliate
     */
    public function setCategory(\Hcuv\JobeetBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return Hcuv\JobeetBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set affiliate
     *
     * @param Hcuv\JobeetBundle\Entity\Affiliate $affiliate
     * @return CategoryAffiliate
     */
    public function setAffiliate(\Hcuv\JobeetBundle\Entity\Affiliate $affiliate = null)
    {
        $this->affiliate = $affiliate;
    
        return $this;
    }

    /**
     * Get affiliate
     *
     * @return Hcuv\JobeetBundle\Entity\Affiliate 
     */
    public function getAffiliate()
    {
        return $this->affiliate;
    }
}
