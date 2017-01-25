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

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RadioShowTimeSlotRepository")
 * @ORM\Table(name="RADIO_SHOW_TIME_SLOT")
 */
class RadioShowTimeSlot
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var RadioShow
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioShow")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     * })
     */
    private $show;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioStation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")
     * })
     *
     */
    private $radioStation;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var Int
     * @ORM\Column(type="integer",nullable=true)
     */
    private $weekOf;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endAt;

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
     * RadioShowTimeSlot constructor.
     */
    public function __construct(){}

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return RadioShow
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * @param RadioShow $show
     */
    public function setShow($show)
    {
        $this->show = $show;
    }

    /**
     * @return mixed
     */
    public function getRadioStation()
    {
        return $this->radioStation;
    }

    /**
     * @param mixed $radioStation
     */
    public function setRadioStation($radioStation)
    {
        $this->radioStation = $radioStation;
    }

    /**
     * @return Int
     */
    public function getWeekOf()
    {
        return $this->weekOf;
    }

    /**
     * @param Int $weekOf
     */
    public function setWeekOf($weekOf)
    {
        $this->weekOf = $weekOf;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param mixed $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return mixed
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param mixed $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}