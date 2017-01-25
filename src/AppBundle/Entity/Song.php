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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 * @ORM\Table(name="SONG")
 *
 * @Gedmo\Loggable
 */
class Song
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
    private $isrc;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $upc;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $featuredArtist;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCopyWritten = false;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $composer;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $album;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $releasedAt;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bitRate;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $sampleRate;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the song.")
     * @Assert\File(mimeTypes={ "audio/mpeg3", "audio/x-mpeg-3", "video/mpeg", "video/x-mpeg", "audio/mp3", "application/octet-stream","audio/mpeg" })
     */
    private $localFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $s3File;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $s3SignatureFile;

    /**
     * @ORM\Column(type="string")
     */
    private $hasDigitalSignature = false;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $reasonForRejection;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $isApproved = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $isPlaylisted = false;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $noted;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $youTubeId;

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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Genre")
     * @ORM\JoinTable(name="SONG_GENRE_MAP",
     *     joinColumns={@ORM\JoinColumn(name="song_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="id")}
     * )
     *
     */
    protected $genres;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecordLabel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_label_id", referencedColumnName="id")
     * })
     */
    private $recordLabel;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\RadioStation")
     * @ORM\JoinTable(name="SONG_RADIO_STATION_TARGETS_MAP",
     *     joinColumns={@ORM\JoinColumn(name="song_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")}
     * )
     *
     */
    protected $targetedRadioStations;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RoyaltyAgency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="royalty_agency_id", referencedColumnName="id")
     * })
     */
    private $royaltyAgency;

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

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * })
     */
    private $createdBy;

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

    public function getIsrc()
    {
        return $this->isrc;
    }

    public function setIsrc($isrc)
    {
        $this->isrc = $isrc;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    public function getFeaturedArtist()
    {
        return $this->featuredArtist;
    }

    public function setFeaturedArtist($featuredArtist)
    {
        $this->featuredArtist = $featuredArtist;
    }

    public function getIsCopyWritten()
    {
        return $this->isCopyWritten;
    }

    public function setIsCopyWritten($isCopyWritten)
    {
        $this->isCopyWritten = $isCopyWritten;
    }

    public function getComposer()
    {
        return $this->composer;
    }

    public function setComposer($composer)
    {
        $this->composer = $composer;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function setAlbum($album)
    {
        $this->album = $album;
    }

    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;
    }

    public function getBitRate()
    {
        return $this->bitRate;
    }

    public function setBitRate($bitRate)
    {
        $this->bitRate = $bitRate;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;
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

    public function getHasDigitalSignature()
    {
        return $this->hasDigitalSignature;
    }

    public function setHasDigitalSignature($hasDigitalSignature)
    {
        $this->hasDigitalSignature = $hasDigitalSignature;
    }

    public function getReasonForRejection()
    {
        return $this->reasonForRejection;
    }

    public function setReasonForRejection($reasonForRejection)
    {
        $this->reasonForRejection = $reasonForRejection;
    }

    public function getIsApproved()
    {
        return $this->isApproved;
    }

    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }

    public function getIsPlaylisted()
    {
        return $this->isPlaylisted;
    }

    public function setIsPlaylisted($isPlaylisted)
    {
        $this->isPlaylisted = $isPlaylisted;
    }

    public function getNoted()
    {
        return $this->noted;
    }

    public function setNoted($noted)
    {
        $this->noted = $noted;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function setGenres($genres)
    {
        $this->genres = $genres;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getRecordLabel()
    {
        return $this->recordLabel;
    }

    public function setRecordLabel($recordLabel)
    {
        $this->recordLabel = $recordLabel;
    }

    public function getTargetedRadioStations()
    {
        return $this->targetedRadioStations;
    }

    public function setTargetedRadioStations($targetedRadioStations)
    {
        $this->targetedRadioStations = $targetedRadioStations;
    }

    public function getRoyaltyAgency()
    {
        return $this->royaltyAgency;
    }

    public function setRoyaltyAgency($royaltyAgency)
    {
        $this->royaltyAgency = $royaltyAgency;
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

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getYouTubeId()
    {
        return $this->youTubeId;
    }

    /**
     * @param mixed $youTubeId
     */
    public function setYouTubeId($youTubeId)
    {
        $this->youTubeId = $youTubeId;
    }

}