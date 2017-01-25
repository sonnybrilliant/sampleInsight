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
namespace AppBundle\Service\User;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CompilerService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var StatusServices
     */
    private $status;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * UserService constructor.
     * @param $logger
     * @param $em
     * @param $status
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        StatusServices $status,
        TokenStorage $tokenStorage)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $id
     * @return \AppBundle\Entity\User|null|object
     * @throws NoResultException
     */
    public function getById($id)
    {
        $user = $this->em->getRepository("AppBundle:User")->find($id);
        if(!$user){
            $this->logger->error("Could not find compiler user by id:".$id);
            throw new NoResultException();
        }

        return $user;
    }

    /**
     * @return mixed
     */
    public function getLoggedInUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param User $user
     * @return User
     */
    public function create(User $user)
    {
        foreach ($user->getUserGroup()->getRoles() as $role) {
            $user->addRole($role);
        }
        $user->setIsRadioStationCompiler(true);
        $user->setStatus($this->status->active());
        $this->em->persist($user);
        $this->em->flush($user);
        return $user;
    }

    /**
     * @param $radioStation
     * @return array
     */
    public function getCompilersByRadioStation($radioStation)
    {
        return $this->em->getRepository("AppBundle:User")->getRadioCompilersByRadioStation($radioStation);
    }

}