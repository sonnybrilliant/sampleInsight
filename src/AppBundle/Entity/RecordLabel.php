<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/27
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecordLabelRepository")
 * @ORM\Table(name="RECORD_LABEL")
 *
 * @Gedmo\Loggable
 * @UniqueEntity(fields={"name"}, groups={"create","edit"}, message="Record label name is already in use, please check available record labels.")
 */
class RecordLabel
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message="Record label name cannot be blank",groups={"create"})
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     */
    private $hiddenName;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $registeredAs;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocal = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAfrican = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAutoCreated = false;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $contactNumber;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $facebook;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     * @Gedmo\Versioned
     */
    private $status;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $country;

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getRegisteredAs()
    {
        return $this->registeredAs;
    }

    public function setRegisteredAs($registeredAs)
    {
        $this->registeredAs = $registeredAs;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getIsMajor()
    {
        return $this->isMajor;
    }

    public function setIsMajor($isMajor)
    {
        $this->isMajor = $isMajor;
    }

    public function getIsLocal()
    {
        return $this->isLocal;
    }

    public function setIsLocal($isLocal)
    {
        $this->isLocal = $isLocal;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getTwitter()
    {
        return $this->twitter;
    }

    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getIsAutoCreated()
    {
        return $this->isAutoCreated;
    }

    public function setIsAutoCreated($isAutoCreated)
    {
        $this->isAutoCreated = $isAutoCreated;
    }

    public function getIsAfrican()
    {
        return $this->isAfrican;
    }

    public function setIsAfrican($isAfrican)
    {
        $this->isAfrican = $isAfrican;
    }

    public function getHiddenName()
    {
        return $this->hiddenName;
    }

    public function setHiddenName($hiddenName)
    {
        $this->hiddenName = $hiddenName;
    }

}