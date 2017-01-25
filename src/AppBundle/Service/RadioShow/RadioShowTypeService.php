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
namespace AppBundle\Service\RadioShow;

use AppBundle\Entity\RadioShowType;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\User\UserService;

class RadioShowTypeService
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
     * @var UserService
     */
    private $userService;

    /**
     * RadioShowTypeService constructor.
     *
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param UserService $userService
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        UserService $userService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->userService = $userService;
    }

    /**
     * @param RadioShowType $radioShowType
     * @return RadioShowType
     */
    public function create(RadioShowType $radioShowType)
    {
        if(!$radioShowType->getCreatedBy()){
            $radioShowType->setCreatedBy($this->userService->getLoggedInUser());
        }

        $this->em->persist($radioShowType);
        $this->em->flush();

        return $radioShowType;
    }

    /**
     * @param $id
     * @return RadioShowType|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:RadioShowType')->find($id);
    }
}