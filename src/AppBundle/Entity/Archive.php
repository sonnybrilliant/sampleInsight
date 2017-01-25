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

use AppBundle\Common\Audio;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArchiveRepository")
 * @ORM\Table(name="ARCHIVE")
 *
 * @Gedmo\Loggable
 */
class Archive extends Audio
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bitrate;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string")
     */
    private $localFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $realFilePath;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $s3File;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $error;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $isUploadedToS3 = false;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $s3SignatureFile;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioStation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")
     * })
     *
     */
    private $radioStation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioShow")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radio_show_id", referencedColumnName="id")
     * })
     *
     */
    private $radioShow;

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
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $uploadedToS3At;

    /**
     * @ORM\Column(type="string")
     */
    private $isUploadedToBucket = true;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $expireAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $playedAt;

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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * @param mixed $bitrate
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getLocalFile()
    {
        return $this->localFile;
    }

    /**
     * @param mixed $localFile
     */
    public function setLocalFile($localFile)
    {
        $this->localFile = $localFile;
    }

    /**
     * @return mixed
     */
    public function getRealFilePath()
    {
        return $this->realFilePath;
    }

    /**
     * @param mixed $realFilePath
     */
    public function setRealFilePath($realFilePath)
    {
        $this->realFilePath = $realFilePath;
    }

    /**
     * @return mixed
     */
    public function getS3File()
    {
        return $this->s3File;
    }

    /**
     * @param mixed $s3File
     */
    public function setS3File($s3File)
    {
        $this->s3File = $s3File;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getIsUploadedToS3()
    {
        return $this->isUploadedToS3;
    }

    /**
     * @param mixed $isUploadedToS3
     */
    public function setIsUploadedToS3($isUploadedToS3)
    {
        $this->isUploadedToS3 = $isUploadedToS3;
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
     * @return mixed
     */
    public function getRadioShow()
    {
        return $this->radioShow;
    }

    /**
     * @param mixed $radioShow
     */
    public function setRadioShow($radioShow)
    {
        $this->radioShow = $radioShow;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return mixed
     */
    public function getUploadedToS3At()
    {
        return $this->uploadedToS3At;
    }

    /**
     * @param mixed $uploadedToS3At
     */
    public function setUploadedToS3At($uploadedToS3At)
    {
        $this->uploadedToS3At = $uploadedToS3At;
    }

    /**
     * @return mixed
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * @param mixed $expireAt
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;
    }

    /**
     * @return mixed
     */
    public function getPlayedAt()
    {
        return $this->playedAt;
    }

    /**
     * @param mixed $playedAt
     */
    public function setPlayedAt($playedAt)
    {
        $this->playedAt = $playedAt;
    }

    /**
     * @return mixed
     */
    public function getS3SignatureFile()
    {
        return $this->s3SignatureFile;
    }

    /**
     * @param mixed $s3SignatureFile
     */
    public function setS3SignatureFile($s3SignatureFile)
    {
        $this->s3SignatureFile = $s3SignatureFile;
    }

    /**
     * @return mixed
     */
    public function getIsUploadedToBucket()
    {
        return $this->isUploadedToBucket;
    }

    /**
     * @param mixed $isUploadedToBucket
     */
    public function setIsUploadedToBucket($isUploadedToBucket)
    {
        $this->isUploadedToBucket = $isUploadedToBucket;
    }
}