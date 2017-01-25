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
namespace AppBundle\Service\AdvertisingOrganization;

use AppBundle\Entity\AdvertisingOrganization;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\User\UserService;

class AdvertisingOrganizationService
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
     * AdvertisingOrganization constructor.
     * @param $logger
     * @param $em
     * @param $userService
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
     * @param $id
     * @return AdvertisingOrganization|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:AdvertisingOrganization')->find($id);
    }

    /**
     * @param AdvertisingOrganization $advertisingOrganization
     * @return AdvertisingOrganization
     */
    public function create(AdvertisingOrganization $advertisingOrganization)
    {
        if(!$advertisingOrganization->getCreatedBy()){
            $advertisingOrganization->setCreatedBy($this->userService->getLoggedInUser());
        }

        $this->em->persist($advertisingOrganization);
        $this->em->flush();
        return $advertisingOrganization;
    }

    /**
     * @param AdvertisingOrganization $advertisingOrganization
     * @return AdvertisingOrganization
     */
    public function update(AdvertisingOrganization $advertisingOrganization)
    {
        $this->em->persist($advertisingOrganization);
        $this->em->flush();
        return $advertisingOrganization;
    }

}