<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/02
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RadioStationStreamRepository")
 * @ORM\Table(name="RADIO_STATION_STREAM",indexes={@ORM\Index(name="radio_stream_search_idx", columns={"artist", "title","isrc","acrid"})})
 */
class RadioStationStream
{

    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $streamId;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioStation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")
     * })
     *
     */
    private $radioStation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ContentType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_type_id", referencedColumnName="id")
     * })
     *
     */
    private $contentType;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $artist;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bucketId;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $count;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $album;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $releaseAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $playedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $isrc;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $upc;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $acrid;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $audioFileId;

    /**
     * @ORM\Column(type="json_array",nullable=true)
     */
    private $data;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $wasAddedViaQueue = true;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $streamURL;

    /**
     * @var Song
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Song")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="song_id", referencedColumnName="id")
     * })
     */
    private $song;

    /**
     * @var Advert
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Advert")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     * })
     */
    private $advert;

    /**
     * @var Slogan
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Slogan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="slogan_id", referencedColumnName="id")
     * })
     */
    private $slogan;

    /**
     * @var Promo
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Promo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promo_id", referencedColumnName="id")
     * })
     */
    private $promo;

    /**
     * @var AdvertisingOrganization
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AdvertisingOrganization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="advertising_organization_id", referencedColumnName="id")
     * })
     */
    private $advertisingOrganization;

    /**
     * @var Artist
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     * })
     */
    private $artistObject;

    /**
     * @var Record label
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecordLabel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_label_id", referencedColumnName="id")
     * })
     */
    private $recordLabel;

    /**
     * @var Radio Show
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioShow")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     * })
     */
    private $show;

    /**
     * @var Archive
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Archive")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="archive_id", referencedColumnName="id")
     * })
     */
    private $archive;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocal = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAfrican = false;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getStreamId()
    {
        return $this->streamId;
    }

    public function setStreamId($streamId)
    {
        $this->streamId = $streamId;
    }

    public function getRadioStation()
    {
        return $this->radioStation;
    }

    public function setRadioStation($radioStation)
    {
        $this->radioStation = $radioStation;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
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

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getReleaseAt()
    {
        return $this->releaseAt;
    }

    public function setReleaseAt($releaseAt)
    {
        $this->releaseAt = $releaseAt;
    }

    public function getPlayedAt()
    {
        return $this->playedAt;
    }

    public function setPlayedAt($playedAt)
    {
        $this->playedAt = $playedAt;
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

    public function getAcrid()
    {
        return $this->acrid;
    }

    public function setAcrid($acrid)
    {
        $this->acrid = $acrid;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getWasAddedViaQueue()
    {
        return $this->wasAddedViaQueue;
    }

    public function setWasAddedViaQueue($wasAddedViaQueue)
    {
        $this->wasAddedViaQueue = $wasAddedViaQueue;
    }

    public function getStreamURL()
    {
        return $this->streamURL;
    }

    public function setStreamURL($streamURL)
    {
        $this->streamURL = $streamURL;
    }

    public function getSong()
    {
        return $this->song;
    }

    public function setSong($song)
    {
        $this->song = $song;
    }

    public function getArtistObject()
    {
        return $this->artistObject;
    }

    public function setArtistObject($artistObject)
    {
        $this->artistObject = $artistObject;
    }

    public function getRecordLabel()
    {
        return $this->recordLabel;
    }

    public function setRecordLabel($recordLabel)
    {
        $this->recordLabel = $recordLabel;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    public function getBucketId()
    {
        return $this->bucketId;
    }

    public function setBucketId($bucketId)
    {
        $this->bucketId = $bucketId;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function getAudioFileId()
    {
        return $this->audioFileId;
    }

    public function setAudioFileId($audioFileId)
    {
        $this->audioFileId = $audioFileId;
    }

    public function getAdvert()
    {
        return $this->advert;
    }

    public function setAdvert($advert)
    {
        $this->advert = $advert;
    }

    public function getAdvertisingOrganization()
    {
        return $this->advertisingOrganization;
    }

    public function setAdvertisingOrganization($advertisingOrganization)
    {
        $this->advertisingOrganization = $advertisingOrganization;
    }

    public function getSlogan()
    {
        return $this->slogan;
    }

    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;
    }

    public function getPromo()
    {
        return $this->promo;
    }

    public function setPromo($promo)
    {
        $this->promo = $promo;
    }

    public function getIsLocal()
    {
        return $this->isLocal;
    }

    public function setIsLocal($isLocal)
    {
        $this->isLocal = $isLocal;
    }

    public function getIsAfrican()
    {
        return $this->isAfrican;
    }

    public function setIsAfrican($isAfrican)
    {
        $this->isAfrican = $isAfrican;
    }

    /**
     * @return mixed
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * @param mixed $show
     */
    public function setShow($show)
    {
        $this->show = $show;
    }

    /**
     * @return mixed
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * @param mixed $archive
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;
    }

}