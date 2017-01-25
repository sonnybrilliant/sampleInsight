<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/05
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RadioStationQueueRepository")
 * @ORM\Table(name="RADIO_STATION_QUEUE")
 *
 * @Gedmo\Loggable
 */
class RadioStationQueue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Song")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="song_id", referencedColumnName="id")
     * })
     */
    private $song;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $isApproved = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $isRejected = false;

    /**
     * @var Artist
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     * })
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecordLabel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_label_id", referencedColumnName="id")
     * })
     */
    private $recordLabel;

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
     */
    private $radioStation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rejected_by_id", referencedColumnName="id")
     * })
     */
    private $rejectedBy;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $rejectedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="approved_by_id", referencedColumnName="id")
     * })
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $approvedAt;

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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSong()
    {
        return $this->song;
    }

    public function setSong($song)
    {
        $this->song = $song;
    }

    public function getIsApproved()
    {
        return $this->isApproved;
    }

    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }

    public function getIsRejected()
    {
        return $this->isRejected;
    }

    public function setIsRejected($isRejected)
    {
        $this->isRejected = $isRejected;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
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

    public function getRejectedBy()
    {
        return $this->rejectedBy;
    }

    public function setRejectedBy($rejectedBy)
    {
        $this->rejectedBy = $rejectedBy;
    }

    public function getRejectedAt()
    {
        return $this->rejectedAt;
    }

    public function setRejectedAt($rejectedAt)
    {
        $this->rejectedAt = $rejectedAt;
    }

    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }

    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    public function setApprovedAt($approvedAt)
    {
        $this->approvedAt = $approvedAt;
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

    public function getRecordLabel()
    {
        return $this->recordLabel;
    }

    public function setRecordLabel($recordLabel)
    {
        $this->recordLabel = $recordLabel;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

}