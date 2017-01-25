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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SloganRepository")
 * @ORM\Table(name="SLOGAN")
 *
 * @Gedmo\Loggable
 * @UniqueEntity(fields={"code"}, groups={"create","edit"}, message="code is already in use, please contact admin.")
 */
class Slogan extends Audio
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
    private $code;

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
     * @ORM\Column(type="integer",nullable=true)
     */
    private $playCount = 0;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the advert.")
     * @Assert\File(mimeTypes={ "audio/mpeg3", "audio/x-mpeg-3", "video/mpeg", "video/x-mpeg", "audio/mp3", "application/octet-stream","audio/mpeg" })
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
     * @ORM\Column(type="string",nullable=true)
     */
    private $arcloudId;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $s3SignatureFile;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $error;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUploadedToBucket = false;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isUploadedToS3 = false;

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
     * @Gedmo\Versioned
     */
    private $status;

    /**
     * @var ContentType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ContentType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_type_id", referencedColumnName="id")
     * })
     * @Gedmo\Versioned
     */
    private $contentType;

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
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $uploadedToBucketAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $uploadedToS3At;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $expireAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastPlayedAt;

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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getPlayCount()
    {
        return $this->playCount;
    }

    public function setPlayCount($playCount)
    {
        $this->playCount = $playCount;
    }

    public function getLocalFile()
    {
        return $this->localFile;
    }

    public function setLocalFile($localFile)
    {
        $this->localFile = $localFile;
    }

    public function getS3File()
    {
        return $this->s3File;
    }

    public function setS3File($s3File)
    {
        $this->s3File = $s3File;
    }

    public function getS3SignatureFile()
    {
        return $this->s3SignatureFile;
    }

    public function setS3SignatureFile($s3SignatureFile)
    {
        $this->s3SignatureFile = $s3SignatureFile;
    }

    public function getIsUploadedToBucket()
    {
        return $this->isUploadedToBucket;
    }

    public function setIsUploadedToBucket($isUploadedToBucket)
    {
        $this->isUploadedToBucket = $isUploadedToBucket;
    }

    public function getIsUploadedToS3()
    {
        return $this->isUploadedToS3;
    }

    public function setIsUploadedToS3($isUploadedToS3)
    {
        $this->isUploadedToS3 = $isUploadedToS3;
    }

    public function getRadioStation()
    {
        return $this->radioStation;
    }

    public function setRadioStation($radioStation)
    {
        $this->radioStation = $radioStation;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
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

    public function getUploadedToBucketAt()
    {
        return $this->uploadedToBucketAt;
    }

    public function setUploadedToBucketAt($uploadedToBucketAt)
    {
        $this->uploadedToBucketAt = $uploadedToBucketAt;
    }

    public function getUploadedToS3At()
    {
        return $this->uploadedToS3At;
    }

    public function setUploadedToS3At($uploadedToS3At)
    {
        $this->uploadedToS3At = $uploadedToS3At;
    }

    public function getExpireAt()
    {
        return $this->expireAt;
    }

    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;
    }

    public function getLastPlayedAt()
    {
        return $this->lastPlayedAt;
    }

    public function setLastPlayedAt($lastPlayedAt)
    {
        $this->lastPlayedAt = $lastPlayedAt;
    }

    public function getBitrate()
    {
        return $this->bitrate;
    }

    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
    }

    public function getRealFilePath()
    {
        return $this->realFilePath;
    }

    public function setRealFilePath($realFilePath)
    {
        $this->realFilePath = $realFilePath;
    }

    public function getArcloudId()
    {
        return $this->arcloudId;
    }

    public function setArcloudId($arcloudId)
    {
        $this->arcloudId = $arcloudId;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}