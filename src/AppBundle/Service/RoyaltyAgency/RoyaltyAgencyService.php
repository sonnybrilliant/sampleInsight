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
namespace AppBundle\Service\RoyaltyAgency;

use AppBundle\Entity\RoyaltyAgency;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;

class RoyaltyAgencyService
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
     * @var UserService
     */
    private $userService;

    /**
     * UserService constructor.
     * @param $logger
     * @param $em
     * @param $status
     * @param $userService
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        StatusServices $status,
        UserService $userService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
    }

    /**
     * @param RoyaltyAgency $royaltyAgency
     * @return RoyaltyAgency
     */
    public function create(RoyaltyAgency $royaltyAgency)
    {
        if(!$royaltyAgency->getCreatedBy()){
            $royaltyAgency->setCreatedBy($this->userService->getLoggedInUser());
        }

        $royaltyAgency->setStatus($this->status->active());
        $this->em->persist($royaltyAgency);
        $this->em->flush();
        return $royaltyAgency;
    }

    /**
     * @param RoyaltyAgency $royaltyAgency
     * @return RoyaltyAgency
     */
    public function update(RoyaltyAgency $royaltyAgency)
    {
        $this->em->persist($royaltyAgency);
        $this->em->flush();
        return $royaltyAgency;
    }

}