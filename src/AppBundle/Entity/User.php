<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="USER",indexes={@ORM\Index(name="user_search_context", columns={"first_name","last_name"})})
 * @UniqueEntity(fields={"email"}, groups={"create"}, message="Email address is already being used by another user, please try another one.")
 *
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User implements AdvancedUserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="First name is required.")
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @Assert\NotBlank(message="Surname is required.")
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string",unique=true)
     * @Gedmo\Slug(fields={"firstName","lastName"})
     */
    private $slug;

    /**
     * @Assert\NotBlank(message="Cellphone is required.")
     * @ORM\Column(type="string",length=20)
     */
    private $msisdn;

    /**
     * @Assert\NotBlank(message="Email address is required.")
     * @Assert\Email(message="Email address is invalid.")
     * @ORM\Column(type="string",unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * A non-persisted field that's used to create the encoded password.
     *
     * @var String
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExpired = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocked = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRadioStationCompiler = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRadioStationAdmin = false;

    /**
     * @var RadioStation
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RadioStation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radio_station_id", referencedColumnName="id")
     * })
     * @Gedmo\Versioned
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
     * @var Gender
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Gender")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gender_id", referencedColumnName="id")
     * })
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_group_id", referencedColumnName="id")
     * })
     */
    private $userGroup;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role")
     * @ORM\JoinTable(name="USER_ROLE_MAP",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $userRoles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCredentialExpired = false;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     * @link https://github.com/stof/StofDoctrineExtensionsBundle
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @link https://github.com/stof/StofDoctrineExtensionsBundle
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiredAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lockedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return ucfirst($this->firstName).' '.ucfirst($this->lastName);
    }

    public function getFullName()
    {
        return ucfirst($this->firstName).' '.ucfirst($this->lastName);
    }


    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return $this->isExpired ? false : true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return $this->isLocked ? false : true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return $this->isCredentialExpired ? false : true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
       $array = [];
       $tmp = $this->userRoles->toArray();
        $arrSize = $tmp ;
       for($x = 0; $x < $arrSize; $x++){
           $array[] = $tmp[$x]->getRole();
       }

       return $array;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getMsisdn()
    {
        return $this->msisdn;
    }

    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        //forces the object to look 'dirty' to Doctrine. Avoids
        //Doctrine **not** saving this entity, if only plainPassword is changed
        $this->password = null;
    }

    public function getIsExpired()
    {
        return $this->isExpired;
    }

    public function setIsExpired($isExpired)
    {
        $this->isExpired = $isExpired;
        if($isExpired){
            $this->setIsActive(false);
        }
    }

    public function getIsLocked()
    {
        return $this->isLocked;
    }

    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;
        if($isLocked){
            $this->setIsActive(false);
        }
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
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

    public function getUserGroup()
    {
        return $this->userGroup;
    }

    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;
    }

    public function getUserRoles()
    {
        return $this->userRoles;
    }

    public function addRole($role)
    {
        $this->userRoles->add($role);
    }

    public function removeRole($role)
    {
        $this->userRoles->removeElement($role);
    }

    /**
     * Clear user roles
     */
    public function clearRoles()
    {
        $this->userRoles->clear();
    }

    public function getIsCredentialExpired()
    {
        return $this->isCredentialExpired;
    }

    public function setIsCredentialExpired($isCredentialExpired)
    {
        $this->isCredentialExpired = $isCredentialExpired;
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

    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;
    }

    public function getLockedAt()
    {
        return $this->lockedAt;
    }

    public function setLockedAt($lockedAt)
    {
        $this->lockedAt = $lockedAt;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function getIsRadioStationCompiler()
    {
        return $this->isRadioStationCompiler;
    }

    public function setIsRadioStationCompiler($isRadioStationCompiler)
    {
        $this->isRadioStationCompiler = $isRadioStationCompiler;
    }

    public function getIsRadioStationAdmin()
    {
        return $this->isRadioStationAdmin;
    }

    public function setIsRadioStationAdmin($isRadioStationAdmin)
    {
        $this->isRadioStationAdmin = $isRadioStationAdmin;
    }

    public function getRadioStation()
    {
        return $this->radioStation;
    }

    public function setRadioStation($radioStation)
    {
        $this->radioStation = $radioStation;
    }

}