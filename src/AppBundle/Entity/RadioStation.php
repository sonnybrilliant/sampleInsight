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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RadioStationRepository")
 * @ORM\Table(name="RADIO_STATION")
 */
class RadioStation
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
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="string")
     */
    private $frequency;

    /**
     * @ORM\Column(type="string")
     */
    private $website;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $stream;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $streamId;

    /**
     * @ORM\Column(type="text")
     */
    private $bio;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Language")
     * @ORM\JoinTable(name="RADIO_STATION_LANGUAGE_MAP",
     *     joinColumns={@ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="language_id", referencedColumnName="id")}
     * )
     *
     */
    protected $languages;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Province")
     * @ORM\JoinTable(name="RADIO_STATION_PROVINCE_MAP",
     *     joinColumns={@ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="province_id", referencedColumnName="id")}
     * )
     *
     */
    protected $provinces;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Genre")
     * @ORM\JoinTable(name="RADIO_STATION_GENRE_MAP",
     *     joinColumns={@ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="id")}
     * )
     *
     */
    protected $genres;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioStationType")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $radioStationType;

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
     */
    private $createdBy;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->provinces = new ArrayCollection();
        $this->languages = new ArrayCollection();

    }

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

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
    }

    public function getStream()
    {
        return $this->stream;
    }

    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    public function addLanguage(Language $language)
    {
        $this->languages->add($language);
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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    public function getProvinces()
    {
        return $this->provinces;
    }

    public function setProvinces($provinces)
    {
        $this->provinces = $provinces;
    }

    public function addProvince(Province $province)
    {
        $this->provinces->add($province);
    }

    public function getRadioStationType()
    {
        return $this->radioStationType;
    }

    public function setRadioStationType($radioStationType)
    {
        $this->radioStationType = $radioStationType;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function setGenres($genres)
    {
        $this->genres = $genres;
    }

    public function addGenre(Genre $genre)
    {
        $this->genres->add($genre);
    }

    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function getStreamId()
    {
        return $this->streamId;
    }

    public function setStreamId($streamId)
    {
        $this->streamId = $streamId;
    }
}