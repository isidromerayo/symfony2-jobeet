<?php

namespace Hcuv\JobeetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hcuv\JobeetBundle\Utils\Jobeet;

/**
 * Hcuv\JobeetBundle\Entity\Category
 */
class Category
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $jobs;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $category_affiliates;

    private $active_jobs;

    private $more_jobs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jobs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category_affiliates = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add jobs
     *
     * @param Hcuv\JobeetBundle\Entity\Job $jobs
     * @return Category
     */
    public function addJob(\Hcuv\JobeetBundle\Entity\Job $jobs)
    {
        $this->jobs[] = $jobs;
    
        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Hcuv\JobeetBundle\Entity\Job $jobs
     */
    public function removeJob(\Hcuv\JobeetBundle\Entity\Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Add category_affiliates
     *
     * @param Hcuv\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates
     * @return Category
     */
    public function addCategoryAffiliate(\Hcuv\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates)
    {
        $this->category_affiliates[] = $categoryAffiliates;
    
        return $this;
    }

    /**
     * Remove category_affiliates
     *
     * @param Hcuv\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates
     */
    public function removeCategoryAffiliate(\Hcuv\JobeetBundle\Entity\CategoryAffiliate $categoryAffiliates)
    {
        $this->category_affiliates->removeElement($categoryAffiliates);
    }

    /**
     * Get category_affiliates
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoryAffiliates()
    {
        return $this->category_affiliates;
    }
    /**
     * to string Object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set active jobs
     *
     * @param $jobs
     */
    public function setActiveJobs($jobs)
    {
        $this->active_jobs = $jobs;
    }
    /**
     * get active jobs
     */
    public function getActiveJobs()
    {
        return $this->active_jobs;
    }
    /**
     * Slug name
     *
     * @return mixed|string
     */
    public function getSlug()
    {
        return Jobeet::slugify($this->getName());
    }
    /**
     * set more jobs
     *
     * @param $jobs
     */
    public function setMoreJobs($jobs)
    {
        $this->more_jobs = $jobs >=  0 ? $jobs : 0;
    }
    /**
     * get more jobs
     *
     * @return mixed
     */
    public function getMoreJobs()
    {
        return $this->more_jobs;
    }
}