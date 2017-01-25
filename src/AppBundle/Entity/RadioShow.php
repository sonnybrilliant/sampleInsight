<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/03
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RadioShowRepository")
 * @ORM\Table(name="RADIO_SHOW")
 *
 * @Gedmo\Loggable
 */
class RadioShow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Show name cannot be blank",groups={"create","edit"})
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCrossOver = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsMonday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsTuesday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsWednesday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsThursday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsFriday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsSaturday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $playsSunday = false;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioStation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")
     * })
     *
     */
    private $radioStation;

    /**
     * @var RadioShowType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioShowType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="show_type_id", referencedColumnName="id")
     * })
     * @Gedmo\Versioned
     */
    private $radioShowType;

    /**
     * @var RadioShowScheduleType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioShowScheduleType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="schedule_type_id", referencedColumnName="id")
     * })
     * @Gedmo\Versioned
     */
    private $radioShowScheduleType;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Show start time cannot be blank",groups={"create","edit"})
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Show end time cannot be blank",groups={"create","edit"})
     */
    private $endTime;

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
     *   @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id")
     * })
     */
    private $updatedBy;

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return ucfirst($this->title);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPlaysMonday()
    {
        return $this->playsMonday;
    }

    public function setPlaysMonday($playsMonday)
    {
        $this->playsMonday = $playsMonday;
    }

    public function getPlaysTuesday()
    {
        return $this->playsTuesday;
    }

    public function setPlaysTuesday($playsTuesday)
    {
        $this->playsTuesday = $playsTuesday;
    }

    public function getPlaysWednesday()
    {
        return $this->playsWednesday;
    }

    public function setPlaysWednesday($playsWednesday)
    {
        $this->playsWednesday = $playsWednesday;
    }

    public function getPlaysThursday()
    {
        return $this->playsThursday;
    }

    public function setPlaysThursday($playsThursday)
    {
        $this->playsThursday = $playsThursday;
    }

    public function getPlaysFriday()
    {
        return $this->playsFriday;
    }

    public function setPlaysFriday($playsFriday)
    {
        $this->playsFriday = $playsFriday;
    }

    public function getPlaysSaturday()
    {
        return $this->playsSaturday;
    }

    public function setPlaysSaturday($playsSaturday)
    {
        $this->playsSaturday = $playsSaturday;
    }

    public function getPlaysSunday()
    {
        return $this->playsSunday;
    }

    public function setPlaysSunday($playsSunday)
    {
        $this->playsSunday = $playsSunday;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getRadioStation()
    {
        return $this->radioStation;
    }

    public function setRadioStation($radioStation)
    {
        $this->radioStation = $radioStation;
    }

    public function getRadioShowType()
    {
        return $this->radioShowType;
    }

    public function setRadioShowType($radioShowType)
    {
        $this->radioShowType = $radioShowType;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
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

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getRadioShowScheduleType()
    {
        return $this->radioShowScheduleType;
    }

    /**
     * @param mixed $radioShowScheduleType
     */
    public function setRadioShowScheduleType($radioShowScheduleType)
    {
        $this->radioShowScheduleType = $radioShowScheduleType;
    }

    /**
     * @return mixed
     */
    public function getIsCrossOver()
    {
        return $this->isCrossOver;
    }

    /**
     * @param mixed $isCrossOver
     */
    public function setIsCrossOver($isCrossOver)
    {
        $this->isCrossOver = $isCrossOver;
    }
}