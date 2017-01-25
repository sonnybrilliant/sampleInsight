<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/01
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistRepository")
 * @ORM\Table(name="ARTIST")
 *
 * @Gedmo\Loggable
 */
class Artist
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBand = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocal = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAfrican = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bioSource;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $twitter;

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
     * @var Gender
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Gender")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gender_id", referencedColumnName="id")
     * })
     * @Gedmo\Versioned
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Genre")
     * @ORM\JoinTable(name="ARTIST_GENRE_MAP",
     *     joinColumns={@ORM\JoinColumn(name="artist_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="id")}
     * )
     *
     */
    protected $genres;

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
     * @ORM\Column(type="string",nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload the artist image as a jpeg.")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $artistImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeezerProcessed = false;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $apiDeezerId;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $apiDeezerImage56x56;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $apiDeezerImage250x250;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $apiDeezerImage500x500;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $apiDeezerImage1000x1000;

    /**
     * Artist constructor.
     */
    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }


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

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    public function getIsBand()
    {
        return $this->isBand;
    }

    public function setIsBand($isBand)
    {
        $this->isBand = $isBand;
    }

    public function getIsLocal()
    {
        return $this->isLocal;
    }

    public function setIsLocal($isLocal)
    {
        $this->isLocal = $isLocal;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function setGenres($genres)
    {
        $this->genres = $genres;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    public function getTwitter()
    {
        return $this->twitter;
    }

    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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

    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    public function getArtistImage()
    {
        return $this->artistImage;
    }

    public function setArtistImage($artistImage)
    {
        $this->artistImage = $artistImage;
    }

    public function getIsAfrican()
    {
        return $this->isAfrican;
    }

    public function setIsAfrican($isAfrican)
    {
        $this->isAfrican = $isAfrican;
    }

    public function getApiDeezerId()
    {
        return $this->apiDeezerId;
    }

    public function setApiDeezerId($apiDeezerId)
    {
        $this->apiDeezerId = $apiDeezerId;
    }

    public function getApiDeezerImage56x56()
    {
        return $this->apiDeezerImage56x56;
    }

    public function setApiDeezerImage56x56($apiDeezerImage56x56)
    {
        $this->apiDeezerImage56x56 = $apiDeezerImage56x56;
    }

    public function getApiDeezerImage250x250()
    {
        return $this->apiDeezerImage250x250;
    }

    public function setApiDeezerImage250x250($apiDeezerImage250x250)
    {
        $this->apiDeezerImage250x250 = $apiDeezerImage250x250;
    }

    public function getApiDeezerImage500x500()
    {
        return $this->apiDeezerImage500x500;
    }

    public function setApiDeezerImage500x500($apiDeezerImage500x500)
    {
        $this->apiDeezerImage500x500 = $apiDeezerImage500x500;
    }

    public function getApiDeezerImage1000x1000()
    {
        return $this->apiDeezerImage1000x1000;
    }

    public function setApiDeezerImage1000x1000($apiDeezerImage1000x1000)
    {
        $this->apiDeezerImage1000x1000 = $apiDeezerImage1000x1000;
    }

    public function getIsDeezerProcessed()
    {
        return $this->isDeezerProcessed;
    }

    public function setIsDeezerProcessed($isDeezerProcessed)
    {
        $this->isDeezerProcessed = $isDeezerProcessed;
    }

    /**
     * @return mixed
     */
    public function getBioSource()
    {
        return $this->bioSource;
    }

    /**
     * @param mixed $bioSource
     */
    public function setBioSource($bioSource)
    {
        $this->bioSource = $bioSource;
    }

}