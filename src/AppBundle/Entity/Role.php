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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 * @ORM\Table(name="ROLE")
 *
 * @UniqueEntity(fields={"role"}, groups={"create"}, message="Role name is already in use, please check available roles.")
 */
class Role implements RoleInterface
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
     * @ORM\Column(type="string",unique=true)
     */
    private $role;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     * @link https://github.com/stof/StofDoctrineExtensionsBundle
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     * @link https://github.com/stof/StofDoctrineExtensionsBundle
     */
    private $updatedAt;

    /**
     * Role constructor.
     * @param $title
     * @param $role
     */
    public function __construct($title, $role)
    {
        $this->title = $title;
        $this->role = $role;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->role;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->role;
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

//    /**
//     * Add users.
//     *
//     * @param User $users
//     *
//     * @return Role
//     */
//    public function addUser(User $users)
//    {
//        $this->users[] = $users;
//        return $this;
//    }
//    /**
//     * Remove users.
//     *
//     * @param User $users
//     */
//    public function removeUser(User $users)
//    {
//        $this->users->removeElement($users);
//    }
//    /**
//     * Get users.
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getUsers()
//    {
//        return $this->users;
//    }
}